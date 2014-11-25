<?php
/**
 * Yelp reviews with public and private messages receiver.
 * This model is for API use only.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpTotalReviewReceiver extends CFormModel
{
    /**
     * @var string $bizId Yelp business id.
     */
    protected $bizId;

    /**
     * @var array $reviews Array of reviews.
     */
    protected $reviews = array();

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly This param is here for compatibility purposes only.
     */
    public function setAttributes($values, $safeOnly=true)
    {
        $this->bizId = $values['business_id'];

        foreach($values['reviews'] as $review) {
            $attributes = array(
                // Review model details
                'review'=>array(
                    // Review details
                    'reviewId'      => $review['review']['id'],
                    'reviewContent' => trim($review['review']['content']),
                    'starRating'    => $review['review']['star_rating'],
                    'reviewDate'    => date('Y-m-d', $this->parseTime($review['review']['date'])),

                    // User details
                    'userId'            => $review['user']['user_id'],
                    'userName'          => $review['user']['user_name'],
                    'userElite'         => isset($review['user']['user_elite']),
                    'userLocation'      => $review['user']['user_location'],
                    'userPhotoLink'     => $review['user']['user_photo_link'],
                    'userFriendCount'   => $review['user']['user_friend_count'],
                    'userReviewCount'   => $review['user']['user_review_count'],

                    // Public comment details
                    'publicCommentContent'  => '',
                    'publicCommentAuthor'   => '',
                    'publicCommentDate'     => '',

                    // Latest message date
                    'latestMessageDate' => '0000-00-00 00:00:00',
                ),

                // Review updates
                'updates'=>array(),

                // Private messages
                'privateMessages'=>array(),

                // Whether review is flagged
                'isFlagged'=>(bool)$review['isFlagged'],

                // Whether last PM is sent by reviewer or biz owner.
                // Reviewer - message goes to inbox, Biz owner - message goes to sent folders
                'pmFolder'=>'inbox',
            );

            // Set lastActionAt attribute (use review date by default)
            $attributes['review']['lastActionAt'] = $attributes['review']['reviewDate'];

            // Public comment (if exists)
            if(!empty($review['public_comment'])) {
                // Extract author from comment author label
                preg_match('/^Comment from (.*?) of/', $review['public_comment']['from'], $matches);

                $attributes['review']['publicCommentContent']   = trim($review['public_comment']['comment']);
                $attributes['review']['publicCommentAuthor']    = isset($matches[1]) ? $matches[1] : $review['public_comment']['from'];
                $attributes['review']['publicCommentDate']      = date('Y-m-d', $this->parseTime($review['public_comment']['date']));

                // Update lastActionAt attribute if public comment has been made
                // By default public comment date is greater than review date
                $attributes['review']['lastActionAt'] = $attributes['review']['publicCommentDate'];
            }

            // Updates (if exist)
            if(!empty($review['previous_reviews'])) {
                foreach($review['previous_reviews'] as $update) {
                    $updateContent = trim($update['content']);
                    $updateDate    = date('Y-m-d', $this->parseTime($update['date']));

                    $attributes['updates'][] = array(
                        'updateId'      => $update['id'],
                        'updateContent' => $updateContent,
                        'updateHash'    => sha1($update['id'].$updateContent),
                        'starRating'    => $update['star_rating'],
                        'updateDate'    => $updateDate,
                    );

                    // Handle lastActionAt attribute
                    if(strtotime($updateDate) > strtotime($attributes['review']['lastActionAt'])) {
                        $attributes['review']['lastActionAt'] = $updateDate;
                    }
                }
            }

            // Private messages (if exist)
            if(!empty($review['private_msg'])) {
                foreach($review['private_msg']['message']['conversations'] as $message) {
                    $messageContent = trim($message['message']['content']);
                    $messageDate    = date('Y-m-d', $this->parseTime($message['message']['time']));

                    $attributes['privateMessages'][] = array(
                        'messageThread' => $review['private_msg']['id'],
                        'messageType'   => $message['type'],
                        'from'          => $message['from'],
                        'messageContent'=> $messageContent,
                        'messageHash'   => sha1($review['user']['user_id'].$messageContent),
                        'messageDate'   => $messageDate,
                        'userId'        => $review['user']['user_id'],
                    );

                    // Handle inbox/sent tabs
                    if($message['from'] == $attributes['review']['userName']) {
                        // Message from reviewer
                        $attributes['pmFolder'] = 'inbox';
                    }
                    else {
                        // Message from biz owner
                        $attributes['pmFolder'] = 'sent';
                    }

                    // Handle lastActionAt attribute
                    if(strtotime($messageDate) > strtotime($attributes['review']['lastActionAt'])) {
                        $attributes['review']['lastActionAt'] = $messageDate;
                    }

                    // Handle latest message date
                    if(strtotime($messageDate) > strtotime($attributes['review']['latestMessageDate'])) {
                        $attributes['review']['latestMessageDate'] = $messageDate;
                    }
                }
            }

            $this->reviews[] = $attributes;
        }
    }

    /**
     * Save reviews.
     * @return boolean Whether all reviews have been saved
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            $profile = Profile::model()->findByYelpId($this->bizId);

            if($profile === null) {
                throw new CException($this->bizId.' does not exist in the database');
            }

            foreach($this->reviews as $review) {
                $reviewModel = $this->getModel($review['review']['reviewId']);

                // Handle reviews model
                $reviewModel->businessId = $profile->userId;
                $reviewModel->setAttributes($review['review']);

                // Handle opened/replied stacks for new reviews
                if($reviewModel->getIsNewRecord() && ($reviewModel->hasPublicComment() || $reviewModel->hasPrivateMessages())) {
                    $reviewModel->markAsReplied();
                }

                // Handle flagged reviews
                if($review['isFlagged'] && !$reviewModel->isFlagged()) {
                    $reviewModel->status     = Review::STATUS_FLAGGED;
                    $reviewModel->flagReason = 'Flagged by Yelp';
                    $reviewModel->flaggedAt  = date('Y-m-d H:i:s');
                }

                // Handle private messages folder
                if($review['pmFolder'] == 'inbox') {
                    // Message from reviewer
                    $reviewModel->moveMessagesThreadToInbox();
                }
                else {
                    // Message from biz owner
                    $reviewModel->moveMessagesThreadToSent();
                }

                // Try to save review model
                if(!$reviewModel->save()) {
                    throw new CException(sprintf('Review (%s) has not been saved', $review['review']['reviewId']));
                }

                // Handle updates
                foreach($review['updates'] as $update) {
                    $updateModel = $this->getUpdateModel($update['updateHash'], $reviewModel);

                    $updateModel->reviewId = $reviewModel->id;
                    $updateModel->setAttributes($update);

                    if(!$updateModel->save()) {
                        throw new CException(sprintf('Update (%s) has not been saved', $update['updateHash']));
                    }
                }

                // Handle private messages
                foreach($review['privateMessages'] as $message) {
                    $messageModel = $this->getMessageModel($message['messageHash'], $reviewModel);

                    $messageModel->reviewId = $reviewModel->id;
                    $messageModel->source = Message::SOURCE_YELP;
                    $messageModel->setAttributes($message);

                    if(!$messageModel->save()) {
                        throw new CException(sprintf('Message (%s) has not been saved', $message['messageHash']));
                    }
                }
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }

    /**
     * Get review model.
     * @param string $reviewId Review id
     * @return object Review
     */
    protected function getModel($reviewId = null)
    {
        if($reviewId === null) {
            return new Review;
        }

        $model = Review::model()->findByReviewId($reviewId);

        return $model === null ? new Review : $model;
    }

    /**
     * Get review update model.
     * @param string $updateHash Update hash
     * @param object $review Review model
     * @return object ReviewUpdate model
     */
    protected function getUpdateModel($updateHash, $review)
    {
        if(isset($review->updates[$updateHash])) {
            return $review->updates[$updateHash];
        }

        return new ReviewUpdate;
    }

    /**
     * Get private messages model.
     * @param string $messageHash Message hash
     * @param object $review Review model
     * @return object Message model
     */
    public function getMessageModel($messageHash, $review)
    {
        if(isset($review->privateMessages[$messageHash])) {
            return $review->privateMessages[$messageHash];
        }

        return new Message;
    }

    /**
     * Parse yelp relative timestamp.
     * @param string $time Yelp time value
     * @return integer Timestamp
     */
    public function parseTime($time)
    {
        $time = str_replace(array('Updated - ', 'Update - '), '', $time);
        $time = str_replace(array('one', 'One'), '1', $time);

        return strtotime($time);
    }
}
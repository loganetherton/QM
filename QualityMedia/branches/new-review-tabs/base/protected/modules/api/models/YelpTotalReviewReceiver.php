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

        if(!isset($values['reviews']) || !is_array($values['reviews'])) {
            Yii::log(sprintf('[%s] Missing reviews', $this->bizId), CLogger::LEVEL_ERROR);
            $this->reviews[] = array();     // Avoid error in save method

            return;
        }

        foreach($values['reviews'] as $review) {
            $reviewContent = trim($review['review']['content']);

            // Skip empty reviews
            if($reviewContent == '') {
                continue;
            }

            // Skip qype users (we can't perform any action on qype users either way)
            if($review['user']['from_qype'] == 1) {
                continue;
            }

            $attributes = array(
                // Review model details
                'review'=>array(
                    // Review details
                    'reviewId'      => $review['review']['id'],
                    'reviewContent' => $reviewContent,
                    'starRating'    => $review['review']['star_rating'],
                    'reviewDate'    => date('Y-m-d', $this->parseTime($review['review']['date'])),
                    'filtered'      => !empty($review['review']['is_filtered']) ? 1 : 0,
                    'deleted'       => Review::DELETED_FALSE,
                    'replyBlocked'      => !empty($review['review']['private_reply_blocked']) ? 1 : 0,

                    // User details
                    'userId'            => trim($review['user']['user_id']),
                    'userName'          => trim($review['user']['user_name']),
                    'userElite'         => isset($review['user']['user_elite']),
                    'userLocation'      => trim($review['user']['user_location']),
                    'userPhotoLink'     => trim($review['user']['user_photo_link']),
                    'userFriendCount'   => $review['user']['user_friend_count'],
                    'userReviewCount'   => $review['user']['user_review_count'],

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

                // Whether message has already been replied by biz owner (with private message or public comment)
                'isReplied'=>false,
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

                // Mark message as replied
                $attributes['isReplied'] = true;

                // Mark status as checked
                $attributes['review']['approvalStatus'] = Review::APPROVAL_STATUS_ACCEPTED;

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

            // Private messages (if exist and have body)
            if(!empty($review['private_msg']) && isset($review['private_msg']['message'])) {
                foreach($review['private_msg']['message']['conversations'] as $message) {
                    $messageContent = iconv('UTF-8', 'ASCII//TRANSLIT', trim($message['message']['content']));

                    $messageFrom    = trim($message['from']);
                    $messageDate    = date('Y-m-d H:i:s', $this->parseTime($message['message']['time']));

                    $messageAttributes = array(
                        'messageType'   => trim($message['type']),
                        'from'          => $messageFrom,
                        'messageContent'=> $messageContent,
                        'messageHash'   => sha1($review['user']['user_id'].$messageContent),
                        'messageDate'   => $messageDate,
                        'userId'        => $review['user']['user_id'],
                    );

                    // Handle inbox/sent tabs
                    if($messageFrom == $attributes['review']['userName']) {
                        // Message from reviewer
                        $attributes['pmFolder'] = 'inbox';
                    }
                    else {
                        // Message from biz owner
                        $attributes['pmFolder'] = 'sent';

                        // Mark message as replied
                        $attributes['isReplied'] = true;
                        $messageAttributes['approvalStatus'] = Message::APPROVAL_STATUS_ACCEPTED;
                    }

                    // Handle lastActionAt attribute
                    if(strtotime($messageDate) > strtotime($attributes['review']['lastActionAt'])) {
                        $attributes['review']['lastActionAt'] = $messageDate;
                    }

                    // Handle latest message date
                    if(strtotime($messageDate) > strtotime($attributes['review']['latestMessageDate'])) {
                        $attributes['review']['latestMessageDate'] = $messageDate;
                    }

                    $attributes['privateMessages'][] = $messageAttributes;
                }
            }

            $this->reviews[] = $attributes;
        }

        // Sort them by newest first for accurately counting pre contract reviews
        usort($this->reviews, function($a, $b) {
            return strtotime($a['review']['reviewDate']) > strtotime($b['review']['reviewDate']) ? -1 : 1;
        });
    }

    /**
     * Save reviews.
     * @return boolean Whether all reviews have been saved
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            $yelpBusiness = YelpBusiness::model()->findByBizId($this->bizId);

            if($yelpBusiness === null) {
                throw new CException($this->bizId.' does not exist in the database');
            }

            // Find the first date of subscription for this user
            $subscription = Subscription::model();
            $subscription->getDbCriteria()->order = 't.activatedAt ASC';
            $subscription->getDbCriteria()->limit = 1;
            $subscription = $subscription->findByAttributes(array('userId' => $yelpBusiness->userId));

            if (empty($subscription)) {
                $contractDate = time();
            }
            else {
                $contractDate = strtotime('-1 month 00:00', strtotime($subscription->activatedAt));
            }

            $reviewsToBeDeleted = $this->getBusinessReviews($yelpBusiness->id);

            foreach($this->reviews as $review) {
                unset($reviewsToBeDeleted[$review['review']['reviewId']]);

                $reviewModel = $this->getModel($review['review']['reviewId']);

                // This is just to be safe and avoid errors for not having default values
                // These will get overwritten later if required
                if ($reviewModel->getIsNewRecord()) {
                    $reviewModel->moveMessagesThreadToInbox();
                    $reviewModel->markAsUnprocessed();
                    $reviewModel->status = Review::STATUS_OPENED;
                }

                // Handler Flag Approval Status
                if((bool)$review['isFlagged'] && $reviewModel->flagApprovalStatus == Review::APPROVAL_STATUS_OPEN) {
                    $reviewModel->flagApprovalStatus = Review::APPROVAL_STATUS_ACCEPTED;
                }

                // Do not override author if already exists
                if($reviewModel->hasPublicComment()) {
                    unset($review['review']['publicCommentAuthor']);
                }

                //Handle posting status for the replies made in the dash
                if(isset($review['review']['publicCommentContent'])) {
                    $review['review']['postingStatus'] = 1;
                }

                //Handle posting status for the replies made in the dash
                if(isset($review['review']['publicCommentContent'])) {
                    $review['review']['postingStatus'] = 1;
                }

                // Handle reviews model
                $reviewModel->businessId     = $yelpBusiness->userId;
                $reviewModel->yelpBusinessId = $yelpBusiness->id;
                $reviewModel->setAttributes($review['review']);

                // Handle opened/replied stacks for new reviews
                if($review['isReplied']) {
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
                    if(count($review['privateMessages']) > $reviewModel->privateMessagesCount) {
                        // Move messages thread to Inbox only if new message has been received.
                        // This condition protectes archived messages to be moved back to Inbox,
                        // when no new message has been received
                        $reviewModel->moveMessagesThreadToInbox();
                    }
                }
                else {
                    // Message from biz owner
                    $reviewModel->moveMessagesThreadToSent();
                }

                // Try to save review model
                if(!$reviewModel->save()) {
                    throw new CException(sprintf('Review (%s) has not been saved [%s]', $review['review']['reviewId'], var_export($reviewModel->getErrors(), true)));
                }

                // Handle updates
                foreach($review['updates'] as $update) {
                    $updateModel = $this->getUpdateModel($update['updateHash'], $reviewModel);

                    $updateModel->reviewId = $reviewModel->id;
                    $updateModel->setAttributes($update);

                    if(!$updateModel->save()) {
                        throw new CException(sprintf('Update (%s) has not been saved [%s]', $update['updateHash'], var_export($updateModel->getErrors(), true)));
                    }
                }

                // Handle private messages
                foreach($review['privateMessages'] as $message) {
                    $messageModel = $this->getMessageModel($message['messageHash'], $reviewModel);
                    $messageModel->reviewId = $reviewModel->id;

                    // Don't override message author if message has been sent from dashboard
                    if($messageModel->isStaffAnswer()) {
                        unset($message['from']);
                    }

                    $messageModel->setAttributes($message);
                    $messageModel->setSource(Message::SOURCE_YELP);

                    // Update posting status
                    if($messageModel->isStaffAnswer() || $messageModel->isBizOwnerAnswer()) {
                        $messageModel->postingStatus = 1;
                    }

                    if(!$messageModel->save()) {
                        throw new CException(sprintf('Message (%s) has not been saved [%s]', $message['messageHash'], var_export($messageModel->getErrors(), true)));
                    }
                }
            }

            $this->removeDeletedReviews($reviewsToBeDeleted);

            //Recalculate client precontract reviews
            $this->recalculatePrecontractReviews();

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log(sprintf('[%s] %s', $this->bizId, $e->getMessage()), CLogger::LEVEL_ERROR);
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
     * Get review ids for provided business.
     * This method is used to remove deleted reviews from dash.
     * @param integer $businessId Business id
     * @return array List of available review ids
     */
    protected function getBusinessReviews($businessId)
    {
        $reviews = Review::model()->yelpBusinessScope($businessId)->dropDownList('reviewId');

        return array_flip($reviews);
    }

    /**
     * Remove deleted reviews from dash.
     * @param array $reviewsToBeDeleted Reviews to be deleted
     */
    protected function removeDeletedReviews($reviewsToBeDeleted)
    {
        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', array_values($reviewsToBeDeleted));

        return Review::model()->updateAll(array('deleted'=>Review::DELETED_TRUE), $criteria);
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

    /**
     * Recalculate client precontract reviews
     * @return boolean Whether the update suceeded
     */
    public function recalculatePrecontractReviews()
    {
        $yelpBusiness = YelpBusiness::model()->findByBizId($this->bizId);

        $clientBillingInfoModel = BillingInfo::model()->findByUserId($yelpBusiness->userId);

        return $clientBillingInfoModel->recalculatePrecontractReviews();
    }
}
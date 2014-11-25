<?php
/**
 * Yelp reviews receiver.
 * This model is for API use only.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpReviewReceiver extends CFormModel
{
    /**
     * @var array $bizId Yelp business id.
     */
    protected $bizId;

    /**
     * @var array $reviews Array of sent reviews.
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
            $content = trim($review['review']['content']);
            $reviewTime = $this->parseTime($review['review']['date']);

            $attributes = array(
                'reviewId'          => $review['review']['id'],
                'reviewHash'        => sha1($content),
                'starRating'        => $review['review']['star_rating'],
                'reviewDate'        => date('Y-m-d', $reviewTime),
                'content'           => $content,
                'userId'            => $review['user']['user_id'],
                'userPhotoLink'     => $review['user']['user_photo_link'],
                'userFriendCount'   => $review['user']['user_friend_count'],
                'userReviewCount'   => $review['user']['user_review_count'],
                'userName'          => $review['user']['user_name'],
                'userYelpProfile'   => $review['user']['user_yelp_profile'],
                'userLocation'      => $review['user']['user_location'],
                'userElite'         => isset($review['user']['user_elite']),
                'flagged'           => (boolean) $review['isFlagged']
            );

            // Public comment has been made
            if(count($review['public_comment']) > 0) {
                // Extract author from comment author
                preg_match('/^Comment from (.*?) of/', $review['public_comment']['from'], $matches);

                $commentTime = $this->parseTime($review['public_comment']['date']);

                $attributes['publicComment']        = $review['public_comment']['comment'];
                $attributes['publicCommentAuthor']  = isset($matches[1]) ? $matches[1] : $review['public_comment']['from'];
                $attributes['publicCommentDate']    = date('Y-m-d', $commentTime);
            }

            if(isset($review['review_private_msg']['id'])) {
                $attributes['privateConversationThread'] = $review['review_private_msg']['id'];
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
                $model = $this->getModel($review['reviewId']);

                // Set attributes
                $model->businessId = $profile->userId;

                //add flaggedAt date and flag reason, if the flag the info was not added yet
                if($review['flagged'] && !$model->flagged) {
                    $review['flaggedAt'] = date('Y-m-d H:i:s');
                    $review['flagReason'] = 'Flagged through Yelp';
                }

                $model->setAttributes($review);

                if(!$model->save()) {
                    throw new CException($review['reviewId'].' has not been saved');
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
     * Get yelp review model.
     * @param string $reviewId Review id
     * @return object YelpReview
     */
    protected function getModel($reviewId = null)
    {
        if($reviewId === null) {
            return new YelpReview;
        }

        $model = YelpReview::model()->findByReviewId($reviewId);

        return $model === null ? new YelpReview : $model;
    }

    /**
     * Parse yelp relative timestamp.
     * @param string $time Yelp time value
     * @return integer Timestamp
     */
    public function parseTime($time)
    {
        $time = str_replace('Updated - ', '', $time);
        $time = str_replace(array('one', 'One'), '1', $time);

        return strtotime($time);
    }
}
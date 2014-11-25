<?php

/**
 * Yelp snapshot model
 * 
 * @author Nitesh Pandey <nitesh@nitesh.com.np>
 */
class ClientDashboard extends CFormModel
{

    /**
     * Retrieves Total Visits
     * 
     * @param type $businessId
     * @param bool $lastThirtyDays If true, only last 30 days records are fetched
     * @return int
     */
    public function getVisits($businessId, $lastThirtyDays = false)
    {
        $model = YelpAnalytics::model()->findByAttributes(
                array(
                        'businessId' => $businessId
        ));

        if (null === $model) {
            return 0;
        }
        else {
            return $model->getVisits($lastThirtyDays);
        }
    }

    /**
     * Get count of responses written 
     * 
     * @param type $businessId
     * @param type $lastThirtyDays
     * @return type
     */
    public function getResponsesWritten($businessId, $lastThirtyDays = false)
    {
        $model = Profile::model()->findByPk($businessId);
        return $model->responsesWritten;
    }

    /**
     * Get customer Interactions count
     * 
     * @param type $businessId
     * @param type $lastThirtyDays
     * @return int
     */
    public function getCustomerInteractions($businessId, $lastThirtyDays = false)
    {
        $model = YelpAnalytics::model()->findByAttributes(
                array(
                        'businessId' => $businessId
        ));

        if (null === $model) {
            return 0;
        }
        else {
            return $model->getCustomerInteractions($lastThirtyDays);
        }
    }

    /**
     * Get last activities count
     * 
     * @param type $businessId
     * @return type
     */
    public function getLastActivities($businessId)
    {
        $criteria        = New CDbCriteria;
        $criteria->order = 'lastActionAt DESC';
        $criteria->limit = 10;
        $model           = Review::model();
        $reviews         = $model->findAllByAttributes(array('businessId' => $businessId), $criteria);
        $activities      = array();
        foreach ($reviews as /* @var $review YelpReview */ $review) {
            $activity = array();
            if ($review->lastActionAt == $review->publicCommentDate) {
                $activity['type']          = 'public';
                $activity['message']       = $review->publicComment;
                $activity['date']          = $review->publicCommentDate;
                $activity['review_author'] = $review->userName;
            }
            else {
                $message = YelpMessage::model()->findByAttributes(array(
                        'reviewId' => $review->id,
                        'messageDate' => $review->lastActionAt
                ));
                if (null !== $message) {
                    $activity['type']          = 'private';
                    $activity['message']       = $message->message;
                    $activity['review_author'] = $review->userName;
                    $activity['date']          = $message->messageDate;
                }
            }
            if (!empty($activity)) {
                $activities[] = $activity;
            }
        }
        return $activities;
    }

}
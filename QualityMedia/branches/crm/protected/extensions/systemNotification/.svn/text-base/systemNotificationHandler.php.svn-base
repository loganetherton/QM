<?php
/**
 * System Notification Handler.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */

class SystemNotificationHandler extends CComponent
{
    public function init()
    {
    }

    /**
     * Makes a new notification
     * @param  int    $accountManagerId Related AM id
     * @param  string $content          Content description
     * @param  string $type             Notification type description
     * @param  string $url              Action url
     * @return boolean action result
     */
    public function notify($accountManagerId, $content, $type, $url = null)
    {
        $attributes = array(
            'accountManagerId' => (int) $accountManagerId,
            'content' => $content,
            'url' => $url,
            'type' => $type,
            'status' => SystemNotification::STATUS_UNREAD
        );

        $model = new SystemNotification;

        $existingNotification = $model->findByType($type);

        if($existingNotification !== null) {
            $model = $existingNotification;
        }

        $model->setAttributes($attributes);

        return $model->save();
    }

    /**
     * Makes a new review Related notification
     * @param  int    $reviewId   Related review id
     * @param  string $type       Notification type
     * @param  string $error      Error code
     * @param  int    $attempts   Try attempts
     * @return boolean action result
     */
    public function reviewNotify($reviewId, $type, $error, $attempts = null)
    {
        $review = Review::model()->findByPk($reviewId);
        $content = $this->getErrorLabel($error);

        $type = sprintf("%s: %s's Review from %s", $type, $review->user->billingInfo->companyName, $review->userName);

        return $this->notify($review->user->accountManagerId, $content, $type);
    }

    /**
     * Makes a new user Related notification
     * @param  int    $userId   Related user id
     * @param  string $type     Notification type
     * @param  string $error    Error code
     * @param  int    $attempts Try attempts
     * @return boolean action result
     */
    public function userNotify($userId, $type, $error, $attempts = null)
    {
        $user = User::model()->findByPk($userId);
        $content = $this->getErrorLabel($error);

        $type = sprintf("%s: %s", $user->billingInfo->companyName, $type);

        return $this->notify($user->accountManagerId, $content, $type);
    }

    /**
     * Sets all Account Manager Notifications as read
     * @param  int $accountManagerId Account Manager Id
     * @return boolean Update Result
     */
    public function markNotificationsAsRead($accountManagerId)
    {
        $model = new SystemNotification;
        return $model->updateAll(array('status'=>SystemNotification::STATUS_READ), 'accountManagerId = :accountManagerId AND status = :status', array(':status' => SystemNotification::STATUS_UNREAD, ':accountManagerId'=>$accountManagerId));
    }

    /**
     * Return status related label
     * @param  [type] $error [description]
     * @return [type]        [description]
     */
    public function getErrorLabel($error)
    {
        $errorLabels = array(
            'logged_out'                => 'Logged out of Yelp while processing reply.',
            'incorrect_password'        => 'Incorrect Password.',
            'missing_login_page'        => 'Yelp maintenance ongoing.',
            'daily_limit_reached'       => 'Daily reply limit reached.',
            'forbidden_access_messages' => 'Messaging for this account disabled.',
            'blank_reply_content'       => 'Reply has no content.',
            'logged_in'                 => 'Logged In.',
            'no_network'                => 'No internet connection.',
            'upload_owner_photo'        => 'No owner photo.',
            'review_not_found'          => 'Review not found in Yelp.',
            'slideshow_present'         => 'Slideshow Present.',
            'incomplete_data'           => 'Incomplete Data.',
            'request_timed_out'         => 'Request Timed Out.',
            'photo_not_found'           => 'Photo Not Found.',
            'success'                   => 'Success',
            'review_already_replied'    => 'Review already been replied to',
            'biz_blocked_by_user'       => 'Reviewer asked not to be contacted',
        );

        $errorReason = '';
        if(in_array($error, array_keys($errorLabels))) {
            $errorReason = $errorLabels[$error];
        }

        return $errorReason;
    }
}
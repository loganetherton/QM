<?php
/**
 * WebUser represents the persistent state for a Web application user.
 * This is WebUser class only for Account Manager only.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class WebUser extends UWebUser
{
    /**
     * @var string|array the URL for login. If using array, the first element should be
     * the route to the login action, and the rest name-value pairs are GET parameters
     * to construct the login URL (e.g. array('/site/login')). If this property is null,
     * a 403 HTTP exception will be raised instead.
     * @see CController::createUrl
     */
    public $loginUrl = array('/am/session/create');

    /**
     * @var string $keyPrefix A prefix for the name of the session variables storing user session data.
     */
    protected $keyPrefix = 'accountManager';

    /**
     * @var string $defaultUrl The default return URL in case it was not set previously.
     */
    protected $defaultUrl = array('/am/session/create');

    /**
     * Get user object.
     * @return object User object
     */
    public function getUser()
    {
        return $this->getState('__user');
    }

    /**
     * Set user object.
     * @param object $user User object
     */
    public function setUser($user)
    {
        $this->setState('__user', $user);
    }

    /**
     * Get system notifications counter.
     * @return integer Number of notifications
     */
    public function getSystemNotificationsCount()
    {
        return $this->getState('__systemNotificationCounter', 0);
    }

    /**
     * Set system notifications counter.
     * @return integer Number of notifications
     */
    public function setSystemNotificationsCount($notificationsCount)
    {
        return $this->setState('__systemNotificationCounter', $notificationsCount);
    }

    /**
     * Get reviews counter.
     * @return integer Number of reviews
     */
    public function getReviewsCount()
    {
        return $this->getState('__reviewCounter', 0);
    }

    /**
     * Set reviews counter.
     * @param integer $reviewsCount Number of new reviews
     */
    public function setReviewsCount($reviewsCount)
    {
        $this->setState('__reviewCounter', $reviewsCount);
    }

    /**
     * Get private messages counter.
     * @return integer Number of private messages
     */
    public function getMessagesCount()
    {
        return $this->getState('__messageCounter', 0);
    }

    /**
     * Set private messages counter.
     * @param integer $messagesCount Number of new private messages
     */
    public function setMessagesCount($messagesCount)
    {
        $this->setState('__messageCounter', $messagesCount);
    }

    /**
     * Check if the user is senior or not
     * @return boolean true if the user is senior, otherwise false
     */
    public function isSenior()
    {
        $user = $this->getUser();
        return ($user->type == AccountManager::TYPE_SENIOR || $user->type == AccountManager::TYPE_SUPER_SENIOR);
    }

    /**
     * Check if the user is super senior or not
     * @return boolean true if the user is super senior, otherwise false
     */
    public function isSuperSenior()
    {
        $user = $this->getUser();
        return ($user->type == AccountManager::TYPE_SUPER_SENIOR);
    }

}
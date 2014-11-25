<?php
/**
 * WebUser represents the persistent state for a Web application user.
 * This is WebUser class only for admins.
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
    public $loginUrl = array('/client/session/create');

    /**
     * @var string $keyPrefix A prefix for the name of the session variables storing user session data.
     */
    protected $keyPrefix = 'client';
    
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
}
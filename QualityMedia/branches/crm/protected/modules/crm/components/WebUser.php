<?php
/**
 * This is the WebUser intended for the current session, can be Admin, AM or Salesman
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 * @author Shitiz Garg <shitiz@qualitymedia.com>
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
    public $loginUrl = array('/crm/session/create');

    /**
     * @var string $keyPrefix A prefix for the name of the session variables storing user session data.
     */
    protected $keyPrefix = 'crm';

    /**
     * Get user object.
     *
     * @access public
     * @param string $role
     * @return object User object
     */
    public function getUser($role = null)
    {
        if (!empty($role))
            return $this->getRole($role);

        $roles = $this->getRoles();
        if (!empty($roles)) {
            $role = array_pop($roles);
            return $role['model'];
        }

        return $this->getState('__user');
    }

    /**
     * Get identity object.
     * @return UserIdentity User object
     */
    public function getIdentity()
    {
        return $this->getState('__identity');
    }

    /**
     * Set identity object.
     * @param UserIdentity $user User object
     */
    public function setIdentity($user)
    {
        $this->setState('__identity', $user);
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
     * Checks whether the current user has a role assigned or not
     *
     * @access public
     * @param string $role
     * @return bool
     */
    public function isAssigned($role)
    {
        return Yii::app()->getAuthManager()->isAssigned($role, $this->getId());
    }

    /**
     * Returns the roles assigned to the current user
     * {@see UserIdentity::getRoles}
     *
     * @access public
     * @return array
     */
    public function getRoles()
    {
        return $this->getIdentity() ? $this->getIdentity()->getRoles() : false;
    }

    /**
     * Returns the a single role's user model
     * {@see UserIdentity::getRole}
     *
     * @access public
     * @param string $role
     * @return User
     */
    public function getRole($role)
    {
        if (!$this->getIdentity())
            return false;

        $roles = $this->getIdentity()->getRoles();
        return isset($roles[$role]) ? $roles[$role]['model'] : false;
    }

    /**
     * Returns a single role's ID
     *
     * @access public
     * @param string $role
     * @return int
     */
    public function getRoleId($role)
    {
        if ($this->getRole($role)) {
            $role = $this->getRole($role);
            return $role->id;
        }
        return false;
    }
}
<?php
/**
 * WebUser represents the persistent state for a Web application user.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UWebUser extends CWebUser
{
    /**
     * @var string $keyPrefix A prefix for the name of the session variables storing user session data.
     */
    protected $keyPrefix;

    /**
     * @var string $defaultUrl The default return URL in case it was not set previously.
     */
    protected $defaultUrl;

    /**
     * Initializes the application component.
     * This method overrides the parent implementation by starting session,
     * performing cookie-based authentication if enabled, and updating the flash variables.
     */
    public function init()
    {
        $this->setStateKeyPrefix($this->keyPrefix);

        parent::init();
    }

    /**
     * Returns the URL that the user should be redirected to after successful login.
     * @param string $defaultUrl The default return URL in case it was not set previously.
     * @return string The URL that the user should be redirected to after login.
     */
    public function getReturnUrl($defaultUrl = null)
    {
        if($defaultUrl === null) {
            $defaultUrl = $this->defaultUrl;
        }

        return parent::getReturnUrl($defaultUrl);
    }
}
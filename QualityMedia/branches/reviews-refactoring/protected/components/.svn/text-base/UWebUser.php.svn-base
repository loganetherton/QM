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
     * Initializes the application component.
     * This method overrides the parent implementation by starting session,
     * performing cookie-based authentication if enabled, and updating the flash variables.
     */
    public function init()
    {
        $this->setStateKeyPrefix($this->keyPrefix);

        parent::init();
    }
}
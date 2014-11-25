<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UserIdentity extends CUserIdentity
{
    public $id;
    public $model;

    /**
     * Authenticates a user based on username and password.
     * This method is required by IUserIdentity.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $model = AccountManager::model()->findByUsername($this->username);

        if($model === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        elseif($model->encryptPassword($this->password, $model->salt) !== $model->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->errorCode = self::ERROR_NONE;
            $this->id = $model->id;
            $this->model = $model;
        }

        return $this->errorCode;
    }

    /**
     * Returns the unique identifier for the identity.
     * @return string the unique identifier for the identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return object Admin model
     */
    public function getModel()
    {
        return $this->model;
    }
}
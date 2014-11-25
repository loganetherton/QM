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
    public $email;
    public $password;
    public $model;

    /**
     * Constructor.
     * Since we use email instead of username we have to override this method
     * @param string $username username
     * @param string $password password
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Authenticates a user based on username and password.
     * This method is required by IUserIdentity.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $model = Client::model()->findByEmail($this->email);

        if($model === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        // This is just a temp code. To be replaced with more robust solution
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
     * Returns the display name for the identity.
     * This method is required by {@link IUserIdentity}.
     * @return string the display name for the identity.
     */
    public function getName()
    {
        return $this->email;
    }

    /**
     * @return object Client model
     */
    public function getModel()
    {
        return $this->model;
    }
}
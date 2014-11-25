<?php
/**
 * Handles salesmen login form data.
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $limitRole;

    protected $userIdentity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('username, password, limitRole', 'required'),
            array('password', 'authenticate', 'skipOnError'=>true),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'username' => 'Username',
            'password' => 'Password',
            'limitRole' => 'Role',
        );
    }

    /**
     * Authenticate password callback.
     */
    public function authenticate($attribute, $params)
    {
        if(!$this->hasErrors()) {
            $this->userIdentity = new UserIdentity($this->username, $this->password, $this->limitRole);
            $error = $this->userIdentity->authenticate();

            if($error == UserIdentity::ERROR_USERNAME_INVALID || $error == UserIdentity::ERROR_PASSWORD_INVALID) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Login (or at least try to) user.
     * @return boolean whether login succeeds.
     */
    public function login()
    {
        if ($this->validate()) {
            $user = Yii::app()->getUser();

            $user->login($this->userIdentity, 0);
            $user->setIdentity($this->userIdentity);

            $this->updateLastVisit();

            return true;
        }

        return false;
    }

    /**
     * Update lastVisit value.
     * @return boolean Wheteher model has been updated
     */
    protected function updateLastVisit()
    {
        foreach ($this->userIdentity->getRoles() as $role) {
            $role['model']->updateLastVisit();
        }
    }
}
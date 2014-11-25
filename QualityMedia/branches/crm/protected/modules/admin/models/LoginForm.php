<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping user login form data.
 * It is used by the 'create' action of 'SessionController'.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class LoginForm extends CFormModel
{
    public $username;
    public $password;

    protected $userIdentity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
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
        );
    }

    /**
     * Authenticate password callback.
     */
    public function authenticate($attribute, $params)
    {
        if(!$this->hasErrors()) {
            $this->userIdentity = new UserIdentity($this->username, $this->password);
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
        if($this->validate()) {
            Yii::app()->getUser()->login($this->userIdentity, 0);
            Yii::app()->getUser()->setUser($this->userIdentity->getModel());

            $this->updateLastVisit($this->userIdentity->getId());

            return true;
        }

        return false;
    }

    /**
     * Update lastVisit value.
     * @param integer $id Admin id
     * @return boolean Wheteher model has been updated
     */
    protected function updateLastVisit($id)
    {
        Admin::model()->findByPk($id)->updateLastVisit();
    }
}
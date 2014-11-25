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
            $user = Yii::app()->getUser();
            $am   = $this->userIdentity->getModel();

            $user->login($this->userIdentity, 0);
            $user->setUser($am);
            $user->setSystemNotificationsCount($am->getSystemNotificationsCount($user->getId()));
            $user->setReviewsCount($am->getReviewsCount());
            $user->setMessagesCount($am->getMessagesCount());

            $this->updateLastVisit($this->userIdentity->getId());

            $reviewDueNotes = Note::model()->getDueReviewNotes($user->getId());
            $clientDueNotes = Note::model()->getDueClientNotes($user->getId());
            if ($reviewDueNotes > 0 || $clientDueNotes > 0) {
                $_SESSION['due_review_notes'] = $reviewDueNotes;
                $_SESSION['due_client_notes'] = $clientDueNotes;
            }

            return true;
        }

        return false;
    }

    /**
     * Update lastVisit value.
     * @param integer $id Account Manager id
     * @return boolean Wheteher model has been updated
     */
    protected function updateLastVisit($id)
    {
        AccountManager::model()->findByPk($id)->updateLastVisit();
    }
}
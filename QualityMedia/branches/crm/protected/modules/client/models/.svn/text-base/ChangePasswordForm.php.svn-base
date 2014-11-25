<?php

/**
 * ChangePasswordForm class.
 * ChangePasswordForm is the data structure for keeping change password form data.
 * It is used by the 'create' action of 'SessionController'.
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */
class ChangePasswordForm extends CFormModel
{

    public $oldPassword;
    public $newPassword;
    public $newPasswordConfirm;
    protected $userIdentity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
                array('oldPassword, newPassword, newPasswordConfirm', 'required'),
                array('newPassword', 'compare', 'compareAttribute' => 'newPasswordConfirm')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
                'oldPassword' => 'Old Password',
                'newPassword' => 'New Password',
                'newPasswordConfirm' => 'Confirm New Password',
        );
    }

    /**
     * Change password callback.
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $userId    = Yii::app()->user->id;
            $userModel = User::model()->findByPk($userId);
            if (!$userModel->changePassword($this->newPassword, $this->oldPassword)) {
                $this->addError('oldPassword', 'Incorrect details supplied.');
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

}
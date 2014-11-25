<?php
/**
 * PasswordForm model handles change password action.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PasswordForm extends AccountManager
{
    public $username;
    public $oldPassword;
    public $verifyPassword;

    protected $originalPassword;

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('oldPassword, password, verifyPassword', 'required'),
            array('oldPassword', 'validateOldPassword'),
            array('verifyPassword', 'compare', 'compareAttribute'=>'password'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'oldPassword' => 'Old password',
            'password' => 'New password',
            'verifyPassword' => 'Confirm new password',
        );
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        $this->originalPassword = $this->password;
        $this->password = null;

        parent::afterFind();
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
        $this->password = $this->encryptPassword($this->password, $this->salt);

        return parent::beforeSave();
    }

    /**
     * Validate if old password is correct.
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     * @return void
     */
    public function validateOldPassword($attribute, $params)
    {
        $oldPassword = $this->encryptPassword($this->oldPassword, $this->salt);

        if($oldPassword !== $this->originalPassword) {
            $this->addError('oldPassword', 'Old password is incorrect.');
        }
    }
}
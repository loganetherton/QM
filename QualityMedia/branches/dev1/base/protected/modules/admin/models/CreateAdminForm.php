<?php
/**
 * Create Admin form.
 * This class overwrites Admin form to handle registration smoothly.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class CreateAdminForm extends Admin
{
    /**
     * @var string $verifyPassword Password verification placeholder.
     */
    public $verifyPassword;

    /**
     * @var string $originalPassword Original Password stored for update.
     */
    protected $originalPassword;

    /**
     * @var string $originalPassword set to use original password to not update it
     */
    protected $useOriginalPassword;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, password, verifyPassword', 'required'),
            array('username', 'length', 'max'=>100),
            array('username', 'match', 'pattern'=>$this->usernameRegExp, 'message'=>'Username may contain letters, digits and hypens. No spacing.'),
            array('username', 'unique'),
            array('password', 'length', 'min'=>5),
            array('verifyPassword', 'compare', 'compareAttribute'=>'password'),
            array('username', 'filter', 'filter'=>'trim'),
            array('firstName, lastName', 'safe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return CMap::mergeArray(parent::attributeLabels(), array(
            'verifyPassword' => 'Confirm Password',
        ));
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
     * Function called before the validation
     * @return boolean
     */
    protected function beforeValidate()
    {
        // If the record is updated and the password fields are empty, use the original password
        if(!$this->isNewRecord && trim($this->password) =='' && trim($this->verifyPassword) =='') {
            $this->password = $this->originalPassword;
            $this->verifyPassword = $this->password;
            $this->useOriginalPassword = true;
        }

        return parent::beforeValidate();
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
        if(!$this->useOriginalPassword) {
            $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
            $this->password = $this->encryptPassword($this->password, $this->salt);
        }

        return parent::beforeSave();
    }
}
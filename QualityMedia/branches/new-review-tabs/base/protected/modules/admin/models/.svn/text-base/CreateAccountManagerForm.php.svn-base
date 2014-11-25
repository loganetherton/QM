<?php
/**
 * Create AccountManager form.
 * This class overwrites AccountManager form to handle registration smoothly.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class CreateAccountManagerForm extends AccountManager
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
     * @var string $currentType set to use check the type change while saving
     */
    protected $currentType;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, password, verifyPassword', 'required'),
            array('username', 'length', 'max'=>100),
            array('type, seniorManagerId', 'numerical', 'integerOnly'=>true),
            array('username', 'match', 'pattern'=>$this->usernameRegExp, 'message'=>'Username may contain letters, digits and hypens. No spacing.'),
            array('username, email', 'unique'),
            array('email', 'email'),
            array('password', 'length', 'min'=>5),
            array('verifyPassword', 'compare', 'compareAttribute'=>'password'),
            array('username', 'filter', 'filter'=>'trim'),
            array('firstName, lastName, email, state, showOnlyLinkedFeeds, seniorManagerId', 'safe')
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

        $this->currentType = $this->type;

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

        //Only Junior Am can have a Senior AM assignment
        if($this->type != self::TYPE_JUNIOR) {
            $this->seniorManagerId = null;
        }

        //If the AM' type is changed to junior, unlink all junior AMs assigned to
        if($this->currentType != $this->type && $this->type == self::TYPE_JUNIOR) {
            AccountManager::model()->updateAll(
                array('seniorManagerId'=>0), 'seniorManagerId=:seniorManagerId', array(':seniorManagerId'=>$this->id)
            );
        }

        return parent::beforeSave();
    }
}
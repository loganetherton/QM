<?php
/**
 * Admin client model.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AdminClient extends User
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
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'length', 'max'=>100),
            array('email', 'email'),
            array('email', 'unique', 'on'=>'insert', 'message'=>'Email has already been used.'),
            array('email', 'filter', 'filter'=>'strtolower'),
            array('accountCode', 'unique', 'on'=>'create'),
            array('accountCode', 'filter', 'filter'=>'trim'),
            array('password', 'length', 'min'=>5),
            array('verifyPassword', 'compare', 'compareAttribute'=>'password'),
            array('salesmanId', 'exist', 'className'=>'Salesman', 'attributeName'=>'id'),
            array('accountManagerId', 'exist', 'className'=>'AccountManager', 'attributeName'=>'id'),
            array('status', 'boolean'),
            array('fullName', 'safe', 'on'=>'search'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Admin the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
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
        // Hash password for new record
        if($this->getIsNewRecord() && $this->salt === null) {
            $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
            $this->password = $this->encryptPassword(self::DEFAULT_PASSWORD, $this->salt);
        }
        else {
            if(!$this->useOriginalPassword) {
                $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
                $this->password = $this->encryptPassword($this->password, $this->salt);
            }
        }

        return parent::beforeSave();
    }
}
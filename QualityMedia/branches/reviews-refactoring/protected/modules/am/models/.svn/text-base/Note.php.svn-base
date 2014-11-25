<?php
/**
 * Model for client notes in account manager
 *
 * Following columns are in Notes table
 * @property integer $id
 * @property integer $accountManagerId
 * @property integer $userId (The client for which this note is created)
 * @proeprty string $note
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $dueAt
 * @property bool $important
 * @property bool $archived
 *
 * Following relations are also available with this model
 * @property AccountManager $accountManager
 * @property User $user
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class Note extends ActiveRecord
{
    const STATUS_ARCHIVED = 1;
    const STATUS_IMPORTANT = 1;

    /**
     * @var string Used fo searching purposes
     */
    public $companyName;

    /**
     * Returns the static model of the specified AR class
     *
     * @static
     * @access public
     * @param string $className
     * @return Note The static model of this class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the table name associated with this model
     *
     * @access public
     * @return string
     */
    public function tableName()
    {
        return 'notes';
    }

    /**
     * Defines basic validation rules for this model
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array(
            array('archived, important', 'boolean'),
            array('accountManagerId, note', 'required'),
            array('accountManagerId', 'exists', 'className' => 'AccountManager'),
            array('userId', 'exists', 'className' => 'User'),
            array('note', 'filter', 'filter' => 'trim'),
        );
    }

    /**
     * Defines relations between other models and this model's fields
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array(
            'accountManager' => array(self::BELONGS_TO, 'AccountManager', 'accountManagerId'),
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }

    /**
     * @access public
     * @return array The individual labels for the attributes
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'accountManagerId' => 'Account Manager',
            'userId' => 'Client',
            'note' => 'Note Text',
            'createdAt' => 'Created at',
            'updatedAt' => 'Updated at',
            'dueAt' => 'Due at',
            'important' => 'Important',
            'archived' => 'Archived',
        );
    }

    /**
     * Defines various scenario scopes for this model
     *
     * @access public
     * @return array
     */
    public function scopes()
    {
        return array(
            'archived' => array(
                'condition' => 'archived = :archived',
                'params' => array(':archived' => self::STATUS_ARCHIVED),
            ),
            'notArchived' => array(
                'condition' => 'archived != :archived',
                'params' => array(':archived' => self::STATUS_ARCHIVED),
            ),
            'important' => array(
                'condition' => 'important = :imp',
                'params' => array(':imp' => self::STATUS_IMPORTANT),
            ),
            'notImportant' => array(
                'condition' => array('important != :imp'),
                'params' => array(':imp' => self::STATUS_IMPORTANT),
            ),
        );
    }

    /**
     * Search for this model
     *
     * @access public
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'accountManager' => array(
                'together' => true,
            ),
            'user' => array(
                'together' => true,
                'with' => array('billingInfo'),
            ),
        );
        $criteria->compare('billingInfo.companyName', $this->companyName, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Default scope for this model, sets the order by default
     *
     * @access public
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'order' => 'archived ASC, dueAt ASC',
        );
    }

    /**
     * Scope for specifying the user(s)
     *
     * @access public
     * @param mixed $user Can be an integer or an array of integers
     * @return Note
     */
    public function users($user)
    {
        if (is_array($user))
            $this->getDbCriteria()->addInCondition('userId', $user);
        else
            $this->getDbCriteria()->compare('userId', $user);

        return $this;
    }

    /**
     * Scope for specifying the account managers
     *
     * @access public
     * @param mixed $manager Can be an integer or an array of integers
     * @return Note
     */
    public function accountManagers($manager)
    {
        if (is_array($manager))
            $this->getDbCriteria()->addInCondition('accountManagerId', $manager);
        else
            $this->getDbCriteria()->compare('accountManagerId', $manager);

        return $this;
    }

    /**
     * Returns whether this note is archived or not
     *
     * @access public
     * @return bool
     */
    public function isArchived()
    {
        return (int) $this->archived == self::STATUS_ARCHIVED;
    }

    /**
     * Returns whether this note is important or not
     *
     * @access public
     * @return bool
     */
    public function isImportant()
    {
        return (int) $this->important == self::STATUS_IMPORTANT;
    }

    /**
     * Performs some basic validation before saving
     *
     * @access public
     * @return bool
     * @throws CException
     */
    public function beforeSave()
    {
        if (strtotime($this->dueAt) && strtotime($this->dueAt) > 0 && strtotime($this->dueAt) < time())
            throw new CException('Due date cannot be before today');

        $this->dueAt = date('Y-m-d H:i:s', strtotime($this->dueAt));

        // Make sure we only have on active note per client
        if ($this->getIsNewRecord() && !empty($this->userId) && $this->countByAttributes(array('userId' => $this->userId, 'archived' => 0)) > 0)
            throw new CException('This user already has an active record');

        // Make sure that this accountManager is assigned to this client and that user
        // is the current user (prevent CSRF)
        if (!empty($this->userId) && (Client::model()->findByPk($this->userId)->accountManagerId != $this->accountManagerId || $this->accountManagerId != Yii::app()->getUser()->id))
            throw new CException('The account manager for this user is not same as the account manager for this note');

        return parent::beforeSave();
    }

    /**
     * Archives this note
     *
     * @access public
     * @param bool $status
     * @return Note
     */
    public function markArchived($status = true)
    {
        $this->archived = $status ? self::STATUS_ARCHIVED : !self::STATUS_ARCHIVED;
        $this->save();

        return $this;
    }

    /**
     * Marks this note as important/not important
     *
     * @access public
     * @param bool $status
     * @return Note
     */
    public function markImportant($status = true)
    {
        $this->important = $status ? self::STATUS_IMPORTANT : !self::STATUS_IMPORTANT;
        $this->save();

        return $this;
    }

    /**
     * Whether this note is due or not
     *
     * @access public
     * @return bool
     */
    public function isDue()
    {
        return strtotime($this->dueAt) > time();
    }
}
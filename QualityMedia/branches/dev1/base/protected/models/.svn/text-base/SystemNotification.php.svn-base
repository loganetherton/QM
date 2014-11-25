<?php
/**
 * This is the model class for table "system_notifications".
 *
 * The followings are the available columns in table 'system_notifications':
 * @property integer $id
 * @property integer $accountManagerId
 * @property string $content
 * @property string $url
 * @property integer $type
 * @property integer $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property AccountManager $accountManager
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class SystemNotification extends ActiveRecord
{

    const STATUS_UNREAD     = 0;
    const STATUS_READ       = 1;
    const STATUS_ARCHIVED   = 2;

    const TYPE_REVIEW = 0;
    const TYPE_UPLOAD_OWNER_PHOTO = 1;

    const TARGET_TYPE_PUBLIC_MESSAGE = 0;
    const TARGET_TYPE_REVIEW = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SystemNotification the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'system_notifications';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('type', 'required'),
            array('accountManagerId', 'exist', 'className'=>'AccountManager', 'attributeName'=>'id'),
            array('content, type', 'length', 'max'=>255),
            array('url', 'url'),
            array('status', 'in', 'range'=>array_keys($this->getStatuses())),
            array('accountManagerId, content, type, createdAt, updatedAt', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array Relational rules.
     */
    public function relations()
    {
        return array(
            'accountManager' => array(self::BELONGS_TO, 'AccountManager', 'accountManagerId'),
        );
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'unviewed'=>array(
                'condition' => 'status = :status',
                'params'    => array(':status' => self::STATUS_UNREAD),
            ),
            'nonArchived'=>array(
                'condition' => 'status != :status',
                'params'    => array(':status' => self::STATUS_ARCHIVED),
            ),
        );
    }

    /**
     * Account Manager scope.
     * @param integer $accountManagerId Account Manager id
     * @return object User
     */
    public function accountManagerScope($accountManagerId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'accountManagerId = :accountManager',
            'params'=>array(':accountManager'=>$accountManagerId),
        ));

        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'accountManagerId' => 'Account Manager',
            'content' => 'Content',
            'url' => 'URL',
            'type' => 'Type',
            'status' => 'Status',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Date Added',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'updatedAt DESC'
            )
        ));
    }

    /**
     * Returns notification by it's type.
     * @param string $type Type
     * @return object CActiveRecord The record found. Null if none is found.
     */
    public function findByType($type)
    {
        return $this->findByAttributes(array('type'=>$type));
    }

    /**
     * @return array List of available types
     */
    public function getStatuses()
    {
        return array(
            self::STATUS_UNREAD   => 'Unread',
            self::STATUS_READ     => 'Read',
            self::STATUS_ARCHIVED => 'Archived',
        );
    }

    /**
     * Get notification status
     * @return string type status
     */
    public function getStatus()
    {
        $statuses = $this->getStatuses();
        return $statuses[$this->status];
    }

    /**
     * Returns true if the notification has been already read
     * @return boolean
     */
    public function isRead()
    {
        return !($this->status == self::STATUS_UNREAD);
    }

    /**
     * Mark a notification as read.
     * @return boolean Whether the saving succeeds
     */
    public function markAsRead()
    {
        $this->status = self::STATUS_READ;

        return $this->save();
    }

    /**
     * Mark a notification as archived.
     * @return boolean Whether the saving succeeds
     */
    public function markAsArchived()
    {
        $this->status = self::STATUS_ARCHIVED;

        return $this->save();
    }
}
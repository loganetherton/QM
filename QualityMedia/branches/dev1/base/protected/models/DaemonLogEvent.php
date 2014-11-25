<?php
/**
 * This is the model class for table "daemon_log_events".
 *
 * The followings are the available columns in table 'daemon_log_events':
 * @property integer $id
 * @property integer $daemonLogId
 * @property string $type
 * @property string $message
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property DaemonLog $daemon
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DaemonLogEvent extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DaemonLogEvent the static model class
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
        return 'daemon_log_events';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('daemonLogId, type, message', 'required'),
            array('daemonLogId', 'exist', 'className'=>'DaemonLog', 'attributeName'=>'id'),
            array('type', 'length', 'max'=>4),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'daemon' => array(self::BELONGS_TO, 'DaemonLog', 'daemonLogId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'daemonLogId' => 'Daemon',
            'type' => 'Type',
            'message' => 'Message',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'newestFirst'=>array(
                'order'=>'t.createdAt DESC, t.id DESC',
            ),
        );
    }

    /**
     * Daemon scope.
     * @param integer $daemon Daemon id
     * @return object self
     */
    public function daemonScope($daemon)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'daemonLogId = :daemon',
            'params'=>array(':daemon'=>$daemon),
        ));

        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param integer $pageSize Page size
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search($pageSize = 25)
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>$pageSize,
            ),
        ));
    }
}
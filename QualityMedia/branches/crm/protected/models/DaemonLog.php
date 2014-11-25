<?php
/**
 * This is the model class for table "daemon_logs".
 *
 * The followings are the available columns in table 'daemon_logs':
 * @property integer $id
 * @property string $name
 * @property string $host
 * @property integer $pid
 * @property string $argv
 * @property string $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property DaemonLogEvent[] $logEvents
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DaemonLog extends ActiveRecord
{
    const STATUS_UNKNOWN = 'unknown';
    const STATUS_RUNNING = 'running';
    const STATUS_DEAD    = 'dead';
    const STATUS_WAITING = 'waiting';
    const STATUS_EXITED  = 'exit';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DaemonLog the static model class
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
        return 'daemon_logs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, host, pid, status', 'required'),
            array('name, host', 'length', 'max'=>255),
            array('pid', 'numerical', 'integerOnly'=>true),
            array('argv', 'length', 'max'=>5000),
            array('status', 'in', 'range'=>array_keys($this->getStatuses())),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'logEvents' => array(self::HAS_MANY, 'DaemonLogEvent', 'daemonLogId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Daemon Name',
            'host' => 'Host',
            'pid' => 'PID',
            'argv' => 'Argv',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Active daemons scope.
     * @return object self
     */
    public function activeScope()
    {
        $this->getDbCriteria()->addInCondition('status', array(self::STATUS_UNKNOWN, self::STATUS_RUNNING, self::STATUS_WAITING));

        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * @return CActiveDataProvider Related DaemonLogEvent objects
     */
    public function getEvents()
    {
        return DaemonLogEvent::model()->daemonScope($this->id)->newestFirst()->search();
    }

    /**
     * @return string Daemon name
     */
    public function getName()
    {
        return sprintf('%s %d', 'Daemon', $this->id);
    }

    /**
     * @return array List of available statuses
     */
    public function getStatuses()
    {
        return array(
            self::STATUS_UNKNOWN => 'Unknown',
            self::STATUS_RUNNING => 'Running',
            self::STATUS_DEAD    => 'Dead',
            self::STATUS_WAITING => 'Waiting',
            self::STATUS_EXITED  => 'Exited',
        );
    }

    /**
     * @return string Daemon status
     */
    public function getStatus()
    {
        $options = $this->getStatuses();

        return isset($options[$this->status]) ? $options[$this->status] : 'Unknown';
    }

    /**
     * @return string Daemon description
     */
    public function getDescription()
    {
        switch($this->status) {
            case self::STATUS_RUNNING:
                $description = 'This daemon is running.';
                break;
            case self::STATUS_WAITING:
                $description = 'This daemon encountered an error recently and is waiting a moment to restart.';
                break;
            case self::STATUS_DEAD:
                $description = 'This daemon is lost or exited uncleanly, and is presumed dead.';
                break;
            case self::STATUS_EXITED:
                $description = 'This daemon exited cleanly.';
                break;
            case self::STATUS_UNKNOWN:
            default:
                $description = 'This daemon has not reported its status recently. It may have exited uncleanly.';
                break;
        }

        return $description;
    }
}
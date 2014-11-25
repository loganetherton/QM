<?php
/**
 * This is the model class for table "phantom_queue".
 *
 * The followings are the available columns in table 'phantom_queue':
 * @property integer $id
 * @property string  $task
 * @property string  $params
 * @property integer $status
 * @property string  $createdAt
 * @property string  $updatedAt
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Queue extends ActiveRecord
{
    const STATUS_WAITING    = 0;
    const STATUS_PROCESSED  = 1;
    const STATUS_FAILED     = 2;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Plan the static model class
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
        return 'phantom_queue';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('task, status', 'required'),
            array('task', 'length', 'max'=>255),
            array('status', 'numerical'),
            array('params', 'safe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'task' => 'Task',
            'params' => 'Params',
            'status' => 'Status',
            'createdAt' => 'Date Added',
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
            'waiting'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_WAITING),
            ),
        );
    }

    /**
     * Mark a queue item as failed.
     * @return boolean Whether the saving succeeds
     */
    public function markAsFailed()
    {
        $this->status = self::STATUS_FAILED;

        return $this->save();
    }

    /**
     * Mark a queue item as processed.
     * @return boolean Whether the saving succeeds
     */
    public function markAsProcessed()
    {
        $this->status = self::STATUS_PROCESSED;

        return $this->save();
    }

    /**
     * Add a new task to the queue.
     * @return boolean Whether the saving succeeds
     */
    public function addToQueue($task, $params = array())
    {
        $this->setAttributes(array(
            'task'   => $task,
            'params' => CJSON::encode($params),
            'status' => self::STATUS_WAITING,
        ));

        return $this->save();
    }
}
<?php
/**
 * This is the model class for table "worker_active_task_history".
 *
 * The followings are the available columns in table 'worker_active_task_history':
 * @property integer $id
 * @property integer $taskId
 * @property string $content
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property WorkerActiveTask $task
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class WorkerActiveTaskHistory extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className Active record class name
     * @return WorkerActiveTaskHistory The static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string The associated database table name
     */
    public function tableName()
    {
        return 'worker_active_task_history';
    }

    /**
     * @return array Validation rules for model attributes
     */
    public function rules()
    {
        return array(
            array('taskId, content', 'required'),
            array('taskId', 'exist', 'className'=>'WorkerActiveTask', 'attributeName'=>'id'),
            array('content', 'length', 'max'=>255),
        );
    }

    /**
     * @return array Relational rules
     */
    public function relations()
    {
        return array(
            'task' => array(self::BELONGS_TO, 'WorkerActiveTask', 'taskId'),
        );
    }

    /**
     * @return array Customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'taskId' => 'Task',
            'content' => 'Content',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * This method is invoked before validation starts.
     * The default implementation calls {@link onBeforeValidate} to raise an event.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return boolean Whether validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    protected function beforeValidate()
    {
        $this->content = Text::limitChars($this->content, 250);

        return parent::beforeValidate();
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
     * Task scope.
     * @param integer $taskId Task id
     * @return object WorkerActiveTaskHistory
     */
    public function taskScope($taskId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'taskId = :task',
            'params'=>array(':task'=>(int)$taskId),
        ));

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
}
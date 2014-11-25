<?php
/**
 * This is the model class for table "worker_active_tasks".
 *
 * The followings are the available columns in table 'worker_active_tasks':
 * @property string $id
 * @property string $taskName
 * @property string $data
 * @property string $leaseOwner
 * @property string $leaseExpires
 * @property integer $failureCount
 * @property string $failureTime
 * @property integer $status
 * @property integer $duration
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property WorkerActiveTaskHistory[] $taskHistory
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class WorkerActiveTask extends ActiveRecord
{
    const DEFAULT_LEASE_DURATION = 60;  // Seconds

    const STATUS_QUEUED     = 0;
    const STATUS_SUCCESS    = 1;
    const STATUS_FAILURE    = 2;
    const STATUS_CANCELLED  = 3;

    /**
     * @var integer $count Placeholder for count agregate.
     */
    public $count = 0;

    /**
     * @var object $exception Store thrown exception.
     */
    protected $exception;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkerActiveTask the static model class
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
        return 'worker_active_tasks';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('taskName, data, failureCount, status, duration', 'required'),
            array('taskName, leaseOwner', 'length', 'max'=>255),
            array('failureCount, duration', 'numerical', 'integerOnly'=>true),
            array('status', 'in', 'range'=>array_keys($this->getStatuses())),
            array('leaseExpires, failureTime', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'taskHistory'=>array(self::HAS_MANY, 'WorkerActiveTaskHistory', 'taskId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'taskName' => 'Task Name',
            'data' => 'Data',
            'leaseOwner' => 'Lease Owner',
            'leaseExpires' => 'Lease Expires',
            'failureCount' => 'Failure Count',
            'failureTime' => 'Failure Time',
            'status' => 'Task Status',
            'duration' => 'Duration',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave()
    {
        parent::afterSave();

        // Save error in history table
        if($this->exception instanceof Exception) {
            $taskHistory = new WorkerActiveTaskHistory;
            $taskHistory->setAttributes(array(
                'taskId'    => $this->id,
                'content'   => $this->exception->getMessage(),
            ));

            $taskHistory->save();
        }
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the onAfterFind event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        parent::afterFind();

        if(trim($this->leaseOwner) == '') {
            $this->leaseOwner = null;
        }
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'queued'=>array(
                'condition'=>'status = :status',
                'params'=>array(':status'=>self::STATUS_QUEUED),
            ),
            'completed'=>array(
                'condition'=>'status = :status',
                'params'=>array(':status'=>self::STATUS_SUCCESS),
            ),
            'failed'=>array(
                'condition'=>'status = :status',
                'params'=>array(':status'=>self::STATUS_FAILURE),
            ),
            'cancelled'=>array(
                'condition'=>'status = :status',
                'params'=>array(':status'=>self::STATUS_CANCELLED),
            ),
            'opened'=>array(
                'condition'=>'status = :queued OR status = :failure',
                'params'=>array(':queued'=>self::STATUS_QUEUED, ':failure'=>self::STATUS_FAILURE),
            ),
            'closed'=>array(
                'condition'=>'status = :success OR status = :cancelled',
                'params'=>array(':success'=>self::STATUS_SUCCESS, ':cancelled'=>self::STATUS_CANCELLED),
            ),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($criteria = null)
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * @return array List of available statuses
     */
    public function getStatuses()
    {
        return array(
            self::STATUS_QUEUED     => 'Queued',
            self::STATUS_SUCCESS    => 'Success',
            self::STATUS_FAILURE    => 'Failure',
            self::STATUS_CANCELLED  => 'Cancelled',
        );
    }

    /**
     * @return string Status label
     */
    public function getStatus()
    {
        $options = $this->getStatuses();

        return isset($options[$this->status]) ? $options[$this->status] : '';
    }

    /**
     * @return string Lease status
     */
    public function getLeaseStatus()
    {
        if($this->status == self::STATUS_SUCCESS) {
            $status = 'Completed';
        }
        else {
            if($this->leaseOwner === null) {
                $status = 'Not Leased';
            }
            else {
                if(strtotime($this->leaseExpires) > time()) {
                    $status = 'Leased';
                }
                else {
                    $status = 'Lease Expired';
                }
            }
        }

        return $status;
    }

    /**
     * @return CActiveDataProvider List of queued tasks grouped by task name
     */
    public function getQueuedTasks()
    {
        $criteria = new CDbCriteria;

        $criteria->select = array('*', 'COUNT(`id`) AS `count`');
        $criteria->group  = 'taskName';
        $criteria->order  = '`count` DESC';

        $this->setDbCriteria($criteria);

        return $this->opened()->search();
    }

    /**
     * @return CActiveDataProvider List of leased tasks
     */
    public function getLeasedTasks()
    {
        $criteria = new CDbCriteria;

        $criteria->addCondition('leaseOwner <> ""');

        $this->setDbCriteria($criteria);

        return $this->opened()->search();
    }

    /**
     * Return list of tasks to be executed.
     * @param integer $limit Tasks limit
     * @return array List of tasks
     */
    public function getTasks($limit = 1)
    {
        $criteria = new CDbCriteria;

        $criteria->index = 'id';
        $criteria->order = 'id ASC';
        $criteria->limit = $limit;

        // Try new tasks first
        $criteria->condition = '`leaseOwner` = ""';

        $tasks = $this->findAll($criteria);

        if(!empty($tasks)) {
            return $tasks;
        }

        // Try tasks with expired leases but not completed
        $criteria->condition = '`leaseExpires` < :now';
        $criteria->params = array(':now'=>date('Y-m-d H:i:s'));

        // Return tasks or an empty array
        return $this->opened()->findAll($criteria);
    }

    /**
     * @return CActiveDataProvider Task error history
     */
    public function getErrorHistory()
    {
        return WorkerActiveTaskHistory::model()->taskScope($this->id)->newestFirst()->search();
    }

    /**
     * Lease tasks.
     * @param array $tasks List of tasks to lease
     * @param string $leaseOwnershipName Lease ownership name
     * @return integer Number of affected rows
     */
    public function leaseTasks($tasks, $leaseOwnershipName)
    {
        $attributes = array(
            'leaseOwner'    => $leaseOwnershipName,
            'leaseExpires'  => date('Y-m-d H:i:s', time() + self::DEFAULT_LEASE_DURATION),
        );

        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', array_keys($tasks));

        $affectedRows = $this->updateAll($attributes, $criteria);

        // Update attributes in tasks
        foreach($tasks as $task) {
            $task->setAttributes($attributes);
        }

        return $affectedRows;
    }

    /**
     * Set lease duration.
     * @param integer $leaseDuration Lease duration
     * @return boolean Whether the saving succeeds
     */
    public function setLeaseDuration($leaseDuration)
    {
        $this->checkLease();

        $this->leaseExpires = date('Y-m-d H:i:s', time() + $leaseDuration);

        return $this->save();
    }

    /**
     * Archive task.
     * @param integer $status Task status
     * @param integer $duration Task duration
     * @return boolean Whether the saving succeeds
     */
    public function archiveTask($status, $duration)
    {
        if($this->getIsNewRecord()) {
            throw new CException("Attempting to archive a task which hasn't been saved!");
        }

        $this->setAttributes(array(
            'status'    => $status,
            'duration'  => $duration,
        ));

        return $this->save();
    }

    /**
     * Set execution exception.
     * @param object $exception Thrown exception
     * @return object $this
     */
    public function setExecutionException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return object Execution exception
     */
    public function getExecutionException()
    {
        return $this->exception;
    }

    /**
     * Execute task.
     */
    public function executeTask($parent)
    {
        // We do this outside of the try-catch because we don't have permission
        // to release the lease otherwise.
        $this->checkLease();

        try {
            $worker = $this->getWorkerInstance();

            // Check maximum number of failures
            $maximumFailures = $worker->getMaximumRetryCount();
            if($maximumFailures !== null) {
                if($this->failureCount > $maximumFailures) {
                    throw new WorkerPermanentFailureException("Task {$id} has exceeded the maximum number of failures ({$maximumFailures}).");
                }
            }

            // Update lease if worker needs more time
            $lease = $worker->getRequiredLeaseTime();
            if($lease !== null) {
                $this->setLeaseDuration($lease);
            }

            $start = microtime(true);
            $worker->executeTask();
            $stop = microtime(true);

            $duration = (int)(1000000 * ($stop - $start));

            $this->archiveTask(self::STATUS_SUCCESS, $duration);
        }
        catch(WorkerPermanentFailureException $e) {
            $this->setExecutionException($e);

            $this->archiveTask(self::STATUS_CANCELLED, 0);
        }
        catch(Exception $e) {
            $this->setExecutionException($e);

            $this->setAttributes(array(
                'failureCount'  => $this->failureCount + 1,
                'failureTime'   => date('Y-m-d H:i:s'),
            ));

            // Determine retry duration
            $retry = $worker->getWaitBeforeRetry($this);
            if($retry === null) {
                $retry = self::DEFAULT_LEASE_DURATION;
            }

            // NOTE: As a side effect, this saves the object.
            $this->setLeaseDuration($retry);
        }

        return $this;
    }

    /**
     * Check if lease is still active.
     * @throws CException if lease is expired
     */
    protected function checkLease()
    {
        if($this->leaseOwner) {
            if(time() >= strtotime($this->leaseExpires)) {
                throw new CException("Trying to update Task {$this->id} ({$this->taskName}) after lease expiration!");
            }
        }
    }

    /**
     * @return object Worker instance
     * @throws WorkerPermanentFailureException on error
     */
    public function getWorkerInstance()
    {
        $class = $this->taskName;

        if(!class_exists($class)) {
            throw new WorkerPermanentFailureException("Task class '{$class}' does not exist!");
        }

        if(!is_subclass_of($class, 'Worker')) {
            throw new WorkerPermanentFailureException("Task class '{$class}' does not extend Worker class.");
        }

        return new $class($this->data);
    }

    /**
     * Schedule task.
     * @param string $taskName Task name
     * @param array $data Task data
     * @return boolean Whether task has been scheduled
     */
    public static function scheduleTask($taskName, $data)
    {
        $model = new self;
        $model->setAttributes(array(
            'leaseOwner'    => '',
            'taskName'      => $taskName,
            'data'          => CJSON::encode($data),
        ));

        return $model->save();
    }
}
<?php
/**
 * This is the model class for table "phantom_queue".
 *
 * The followings are the available columns in table 'phantom_queue':
 * @property integer $id
 * @property string  $task
 * @property string  $params
 * @property integer $status
 * @property integer $errorReason
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

    const ERRORS_PROFILE_ATTEMPTS_LIMIT = 3;
    const ERRORS_PROFILE = 'photo_not_found,review_not_found,incomplete_data,daily_limit_reached';
    //time buffer in hours
    const ERRORS_PROFILE_TIME_BUFFER    = 24;

    const ERRORS_PROFILE_IMPORTANT_ATTEMPTS_LIMIT = 3;
    const ERRORS_PROFILE_IMPORTANT = 'incorrect_password,upload_owner_photo';
    //time buffer in hours
    const ERRORS_PROFILE_IMPORTANT_TIME_BUFFER    = 1;

    const ERRORS_SYSTEM_ATTEMPTS_LIMIT = 3;
    const ERRORS_SYSTEM  = 'logged_in,logged_out,missing_login_page,no_network,request_timed_out';
    //time buffer in hours
    const ERRORS_SYSTEM_TIME_BUFFER    = 1;

    const ERRORS_OTHER_ATTEMPTS_LIMIT = 3;
    const ERRORS_OTHER   = 'review_already_replied,biz_blocked_by_user,blank_reply_content,slideshow_present';

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
            array('task, errorReason', 'length', 'max'=>255),
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
            'errorReason' => 'Error Reason',
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
                'condition'=> 't.status = :status',
                'params'=> array(':status'=>self::STATUS_WAITING),
            )
        );
    }

    public function readyToExecuteScope()
    {
        $nowTime = date('Y-m-d H:i:s');
        $systemErrors = implode(',', array_map(function($input) {return sprintf('"%s"', $input);}, explode(',', self::ERRORS_SYSTEM)));
        $profileErrors = implode(',', array_map(function($input) {return sprintf('"%s"', $input);}, explode(',', self::ERRORS_PROFILE)));
        $profileImportantErrors = implode(',', array_map(function($input) {return sprintf('"%s"', $input);}, explode(',', self::ERRORS_PROFILE_IMPORTANT)));

        $this->getDbCriteria()->mergeWith(array(
            'condition' =>'(t.status = :statusWaiting)
                OR (t.status = :statusFailed AND DATE_ADD( t.updatedAt, INTERVAL :systemErrorsTimeBuffer HOUR ) < :nowTime AND t.errorReason IN ('.$systemErrors.')) AND t.attempts < :systemErrorsAttemptsLimit
                OR (t.status = :statusFailed AND DATE_ADD( t.updatedAt, INTERVAL :profileErrorsTimeBuffer HOUR ) < :nowTime AND t.errorReason IN ('.$profileErrors.')) AND t.attempts < :profileErrorsAttemptsLimit
                OR (t.status = :statusFailed AND DATE_ADD( t.updatedAt, INTERVAL :profileImportantErrorsTimeBuffer HOUR ) < :nowTime AND t.errorReason IN ('.$profileImportantErrors.')) AND t.attempts < :profileImportantErrorsAttemptsLimit',
            'params'    => array(
                ':statusWaiting' => self::STATUS_WAITING,
                ':statusFailed'  => self::STATUS_FAILED,
                ':nowTime'       => $nowTime,
                ':systemErrorsTimeBuffer'     => self::ERRORS_SYSTEM_TIME_BUFFER,
                ':systemErrorsAttemptsLimit'  => self::ERRORS_SYSTEM_ATTEMPTS_LIMIT,
                ':profileErrorsTimeBuffer'    => self::ERRORS_PROFILE_TIME_BUFFER,
                ':profileErrorsAttemptsLimit' => self::ERRORS_PROFILE_ATTEMPTS_LIMIT,
                ':profileImportantErrorsTimeBuffer'    => self::ERRORS_PROFILE_IMPORTANT_TIME_BUFFER,
                ':profileImportantErrorsAttemptsLimit' => self::ERRORS_PROFILE_IMPORTANT_ATTEMPTS_LIMIT
            ),
        ));

        return $this;
    }

    /**
     * Mark a queue item as failed.
     * @return boolean Whether the saving succeeds
     */
    public function markAsFailed($reason = null)
    {
        $this->status = self::STATUS_FAILED;
        $this->attempts += 1;

        if(!empty($reason)) {
            $this->errorReason = $reason;
        }

        return $this->save();
    }

    /**
     * Mark a queue item as processed.
     * @return boolean Whether the saving succeeds
     */
    public function markAsProcessed()
    {
        $this->status = self::STATUS_PROCESSED;
        $this->attempts += 1;

        return $this->save();
    }

    /**
     * Add a new task to the queue.
     * @return boolean Whether the saving succeeds
     */
    public function addToQueue($task, $params = array())
    {
        $model = new WorkerActiveTask;

        switch($task) {
            case 'publicComment':
                $workerTask = 'PhantomJsPublicCommentWorker';
                $workerParams = array(
                    'id'    => $params[0],
                );
                break;
            case 'privateMessage':
                $workerTask = 'PhantomJsPrivateMessageWorker';
                $workerParams = array(
                    'id'    => $params[0],
                );
                break;
            case 'flagReview':
                $workerTask = 'PhantomJsFlagReviewWorker';
                $workerParams = array(
                    'id'            => $params[0],
                    'message'       => $params[1],
                    'reasonCategory'=> $params[2],
                );
                break;
            case 'updateInfo':
                $workerTask = 'PhantomJsUpdateInfoWorker';
                $workerParams = array(
                    'businessId'    => $params[0],
                    'yelpBusinessId'=> $params[1],
                );
                break;
            case 'savePhotos':
                $workerTask = 'PhantomJsSavePhotosWorker';
                $workerParams = array(
                    'businessId'    => $params[0],
                    'yelpBusinessId'=> $params[1],
                );
                break;
            default:
                $workerParams = array();
                break;
        }

        $model->setAttributes(array(
            'taskName'  => $workerTask,
            'data'      => CJSON::encode($workerParams),
        ));

        return $model->save();
    }

    /**
     * Check if the user data has been changed since last queue execution
     * @return boolean [description]
     */
    public function isReadyToExecute()
    {

        $profileImportantErrors = explode(',', self::ERRORS_PROFILE_IMPORTANT);

        if(in_array($this->errorReason, $profileImportantErrors )) {

            $params = CJSON::decode($this->params);


            switch($this->errorReason) {
                case 'incorrect_password':

                    switch ($this->task) {
                        case 'savePhotos':
                        case 'updateInfo':
                            $userId = $params[0];
                            $userYelpProfile = Profile::model()->findByuserId($userId);
                        break;

                        case 'flagReview':
                        case 'publicComment':
                            $userYelpProfile = Review::model()->findByPk((int) $params[0])->user->profile;
                        break;

                        case 'privateMessage':
                            $message = Message::model()->findByPk((int) $params[0]);
                            $userYelpProfile = $message->review->user->profile;
                        break;
                    }

                    if(strtotime($this->updatedAt) > strtotime($userYelpProfile->updatedAt)) {
                        return false;
                    }
                break;

                case 'upload_owner_photo':

                    switch ($this->task) {
                        case 'savePhotos':
                        case 'updateInfo':
                            $userId = $params[0];
                        break;

                        case 'flagReview':
                        case 'publicComment':
                            $userId = Review::model()->findByPk((int) $params[0])->user->id;
                        break;

                        case 'privateMessage':
                            $userId = Message::model()->findByPk((int) $params[0])->review->user->id;
                        break;
                    }

                    $lastPhoto = YelpPhoto::model()->business($userId)->owner()->uploaded()->find(array('order' => 'updatedAt DESC'));

                    if($lastPhoto === null || strtotime($this->updatedAt) > strtotime($lastPhoto->updatedAt)) {
                        return false;
                    }

                break;
            }

        }

        return true;
    }
}
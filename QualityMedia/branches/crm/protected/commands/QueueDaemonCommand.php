<?php
/**
 * PhantomJs Queue command tool
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class QueueDaemonCommand extends DaemonCommand
{
    const ATTEMPTS = 3;

    /**
     * @var string $pidFile PID file name.
     */
    public $pidFile = 'queueDaemon.pid';

    /**
     * @var string $model Model name.
     */
    protected $model = 'Queue';

    /**
     * Executes the tasks from the queue
     * @return void
     */
    public function actionIndex()
    {
        $tasks = ActiveRecord::model($this->model)->readyToExecuteScope()->findAll();

        $systemNotification = Yii::app()->getComponent('systemNotification');

        foreach($tasks as $task) {
            $params = json_decode($task->params);

            if($params === null) {
                $this->log("JSON for task {$task->id} cannot be decoded");
                $task->markAsFailed();

                continue;
            }

            if(!$task->isReadyToExecute()) {
                continue;
            }

            $counter = 1;
            $executionStatus = true;
            $notificationStatus = 'success';

            $taskStatus = false;

            while($taskStatus !== true) {

                if($counter++ > self::ATTEMPTS) {
                    $executionStatus = false;
                    break;
                }

                $taskResponse = $this->executeTask($task->task, $params);
                $taskStatus = (isset($taskResponse['status']) && $taskResponse['status'] == 'success');
            }

            if($executionStatus) {
                $queueItem = $task->markAsProcessed();
            }
            else {
                $queueItem = $task->markAsFailed($taskResponse['error']);
                $notificationStatus = $taskResponse['error'];
            }

            $attempts = $counter-1;

            //make a notification
            switch ($task->task) {
                case 'privateMessage':
                    $message = Message::model()->findByPk((int) $params[0]);
                    $systemNotification->reviewNotify($message->reviewId, 'Private Message', $notificationStatus, $attempts);
                break;
                case 'publicComment':
                    $systemNotification->reviewNotify($params[0], 'Public Comment', $notificationStatus, $attempts);
                break;
                case 'flagReview':
                    $systemNotification->reviewNotify($params[0], 'Flag Review', $notificationStatus, $attempts);
                break;
                case 'savePhotos':
                    $systemNotification->userNotify($params[0], 'Save Photos', $notificationStatus, $attempts);
                break;
                case 'updateInfo':
                    $systemNotification->userNotify($params[0], 'Update Info', $notificationStatus, $attempts);
                break;
            }

        }
    }

    /**
     * Executes the custom task from PhantomJsCommand Class
     * @param  strin $task task name
     * @param  array  $params optional command parameters
     * @return boolean depending of the result is success or not
     */
    protected function executeTask($task, $params = array())
    {
        $path = 'php '.dirname(__FILE__).'/../yiic phantomJs';

        $this->log("[Queue Task] {$task}");

        $command = "{$path} {$task}";

        //Add params to the command if passed
        if(count($params)) {
            $params = array_map(function($item) {
                if(!is_numeric($item)) {
                    $item = escapeshellarg($item);
                }

                return $item;
            }, $params);

            $command .= " ".implode(" ", $params);
        }

        $this->log("[Queue Task] [Command] {$command}");
        $result = exec($command);

        $this->log("[Queue Task] {$task} {$result}");

        echo $result;

        $responseObj = CJSON::decode($result);

        $response = array('status' => false, 'error' => null);

        if(isset($responseObj['status']) && $responseObj['status'] == 'success') {
            $response['status'] = true;
        }
        else {
            if(isset($responseObj['error']) && !empty($responseObj['error'])) {
                $response['error'] = $responseObj['error'];
            }
        }

        return $response;
    }
}
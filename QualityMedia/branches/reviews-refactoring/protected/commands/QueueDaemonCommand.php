<?php
/**
 * PhantomJs Queue command tool.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class QueueDaemonCommand extends DaemonCommand
{
    protected $tryTimes = 3;
    public $pidFile = 'queueDaemon.pid';

    /**
     * Executes the tasks from the queue
     * @return void
     */
    public function actionIndex() {
        $tasks = Queue::model()->waiting()->findAll();

        foreach($tasks as $task) {

            $params = array();
            $counter = 1;

            if(strlen(trim($task->params))) {
                $params = array_merge($params, json_decode($task->params));
            }

            $executionStatus = true;

            while($this->executeTask($task->task, $params) !== true) {

                $counter++;
                echo "\n";

                if($counter > $this->tryTimes) {

                    $executionStatus = false;
                    break;
                }
            }

            if($executionStatus) {
                $queueItem = $task->markAsProcessed();
            }
            else {
                $queueItem = $task->markAsFailed();
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
                    $item = '"'.$item.'"';
                }
                return $item;
            }, $params);

            $command .= " ".implode(" ", $params);
        }

        $result = exec($command);

        $this->log("[Queue Task] {$task} {$result}");

        echo $result;

        $responseObj = CJSON::decode($result);

        if(isset($result['status']) && $result['status'] == 'success') {
            return true;
        }

        return false;
    }
}
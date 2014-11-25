<?php
/**
 * Taskmaster daemon.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.components.worker.*');
Yii::import('application.workers.*');

class TaskmasterDaemon extends Daemon
{
    /**
     * Run the daemon.
     */
    public function run()
    {
        $sleep = 0;

        do {
            $workerLease = new WorkerLease;
            $tasks = $workerLease->getAndLeaseTasks();

            if($tasks) {
                foreach($tasks as $task) {
                    $id     = $task->id;
                    $class  = $task->taskName;

                    $this->log("Working on task {$id} ({$class})...");

                    $task = $task->executeTask($this);
                    $exception = $task->getExecutionException();

                    if($exception) {
                        if($exception instanceof WorkerPermanentFailureException) {
                            $this->log("Task {$id} failed permanently: {$exception->getMessage()}");
                        }
                        else {
                            $this->log("Task {$id} failed!");

                            throw new CException("Error while executing task ID {$id} from queue: {$exception->getMessage()}");
                        }
                    }
                    else {
                        $this->log("Task {$id} complete! Marked as completed.");
                    }
                }

                $sleep = 1;
            }
            else {
                // $this->log('Tasks queue is empty');
                $sleep = min($sleep + 1, 30);
            }

            $this->sleep($sleep);
        } while(true);
    }
}
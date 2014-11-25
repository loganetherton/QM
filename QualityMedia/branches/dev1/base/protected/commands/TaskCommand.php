<?php
/**
 * Task command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.components.worker.*');
Yii::import('application.workers.*');

class TaskCommand extends CConsoleCommand
{
    /**
     * Execute task.
     * @param integer $id Task id
     */
    public function actionExecute($args)
    {
        $model = new WorkerActiveTask;

        $task = $model->findByPk($args[0]);

        if($task === null) {
            throw new CException("Task {$args[0]} does not exist\n");
        }

        $model->leaseTasks(array($task->id => $task), 'TaskCommand');

        $task = $task->executeTask($this);
        $exception = $task->getExecutionException();

        if($exception) {
            printf("[%s] %s\n", get_class($exception), $exception->getMessage());
        }
    }
}
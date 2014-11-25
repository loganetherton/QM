<?php
/**
 * Daemon tasks controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class TaskController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new WorkerActiveTask('search');
        $model->unsetAttributes();

        if(isset($_GET['WorkerActiveTask'])) {
            $model->setAttributes($_GET['WorkerActiveTask']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * View task.
     * @param integer $id Task id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, 'WorkerActiveTask');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
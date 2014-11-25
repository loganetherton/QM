<?php
/**
 * Daemons  controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DaemonController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new DaemonLog('search');
        $model->unsetAttributes();

        if(isset($_GET['DaemonLog'])) {
            $model->setAttributes($_GET['DaemonLog']);
        }

        $model->activeScope();

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * View daemon.
     * @param integer $id Daemon id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, 'DaemonLog');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
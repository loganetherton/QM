<?php
/**
 * System controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class SystemController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $this->layout = null;

        $model = new Review('search');

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Messages list
     */
    public function actionMessages()
    {
        $this->layout = null;

        $model = new Message('search');

        $this->render('messages', array(
            'model'=>$model,
        ));
    }

    public function actionPhantomqueue()
    {
        $this->layout = null;

        $query = "SELECT * FROM phantom_queue ORDER BY id DESC";

        $dataProvider = new CSqlDataProvider($query, array(
        'pagination'=>array( 'pageSize'=>30, ),));

        $this->render('phantomqueue', array(
            'dataProvider'=>$dataProvider,
        ));
    }

    public function actionPlanSync() {
        Plan::model()->sync();
        echo 'Synchronized';
    }
}
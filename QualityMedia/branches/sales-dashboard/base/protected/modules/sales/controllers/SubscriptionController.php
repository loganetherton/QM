<?php
/**
 * Subscription controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SubscriptionController extends SalesController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Subscription('search');
        $model->unsetAttributes();

        if(isset($_GET['Subscription'])) {
            $model->setAttributes($_GET['Subscription']);
        }

        $model->salesmanScope(Yii::app()->getUser()->getId());

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * View action.
     * @param integer $id Model id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, 'Subscription');

        $this->render('view', array(
            'model'=>$model,
        ));
    }


    /**
     * New subscription action.
     * @param integer $id Model id
     */
    public function actionNew()
    {
        $this->render('new');
    }
}
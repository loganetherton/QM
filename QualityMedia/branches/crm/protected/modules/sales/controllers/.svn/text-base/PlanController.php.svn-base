<?php
/**
 * Recurly subscription plan controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PlanController extends SalesController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Plan('search');
        $model->unsetAttributes();

        if(isset($_GET['Plan'])) {
            $model->setAttributes($_GET['Plan']);
        }

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
        $model = $this->loadModel($id, 'Plan');

        $this->render('view', array(
            'model'=>$model,
        ));
    }

    /**
     * Returns link to salesman custom registration form.
     * @param object $data Model instance
     * @return string Absolute URL to salesman custom registration form
     */
    protected function getCustomFormLink($data)
    {
        return $this->createAbsoluteUrl('/registration/create', array('plan'=>$data->planCode, 'salesman'=>Yii::app()->getUser()->getName()));
    }
}
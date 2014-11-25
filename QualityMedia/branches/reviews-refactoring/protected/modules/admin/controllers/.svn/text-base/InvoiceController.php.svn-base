<?php
/**
 * Invoice controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class InvoiceController extends AdminController
{
    /**
     * List all models.
     */
    public function actionIndex()
    {
        $model = new Invoice('search');

        if(isset($_GET['Invoice'])) {
            $model->setAttributes($_GET['Invoice']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * View model.
     * @param integer $id Model id
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id, 'Invoice');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
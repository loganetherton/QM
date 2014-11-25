<?php
/**
 * Transaction management controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class TransactionController extends AdminController
{
    /**
     * List all models.
     */
    public function actionIndex()
    {
        $model = new Transaction('search');
        $model->unsetAttributes();

        if(isset($_GET['Transaction'])) {
            $model->setAttributes($_GET['Transaction']);
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
        $model = $this->loadModel($id, 'Transaction');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
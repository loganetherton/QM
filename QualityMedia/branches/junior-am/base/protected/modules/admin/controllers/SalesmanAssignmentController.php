<?php
/**
 * Salesmen assignments controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SalesmanAssignmentController extends AdminController
{
    /**
     * Index action.
     * Show unassigned users.
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();

        if(isset($_GET['User'])) {
            $model->setAttributes($_GET['User']);
        }

        $model->unassignedSalesman();

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Create action.
     * Assign user to salesman.
     * @param integer $id Model id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'SalesmanAssignment');

        if(isset($_POST['SalesmanAssignment'])) {
            $model->setAttributes($_POST['SalesmanAssignment']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Sales Rep. has been successfully assigned');

                $this->redirect(array('clientOverview/index'));
            }
        }

        $salesmen = new Salesman;

        $this->render('update', array(
            'model'=>$model,
            'salesmen'=>$salesmen,
        ));
    }
}
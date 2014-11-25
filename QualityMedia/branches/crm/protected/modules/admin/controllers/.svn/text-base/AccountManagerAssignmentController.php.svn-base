<?php
/**
 * Account Managers assignments controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AccountManagerAssignmentController extends AdminController
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

        $model->unassignedAccountManager();

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Create action.
     * Assign user to account Manager.
     * @param integer $id Model id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'AccountManagerAssignment');

        if(isset($_POST['AccountManagerAssignment'])) {
            $model->setAttributes($_POST['AccountManagerAssignment']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Account Manager has been successfully assigned');

                $this->redirect(array('clientOverview/index'));
            }
        }

        $accountManagers = new AccountManager;

        $this->render('update', array(
            'model'=>$model,
            'accountManagers'=>$accountManagers,
        ));
    }
}
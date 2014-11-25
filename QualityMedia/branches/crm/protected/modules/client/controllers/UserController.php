<?php
/**
 * User controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UserController extends ClientController
{
    /**
     * View model.
     * @param integer $id Model id
     */
    public function actionView($id = null)
    {
        // For now allow only own profile view
        $id = Yii::app()->getUser()->getId();

        $model = $this->loadModel($id, 'User');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
    
    public function actionPreferences()
    {
        $id = Yii::app()->getUser()->getId();
        $model = new ChangePasswordForm;
        $user = Yii::app()->user;
        if(isset($_POST['ChangePasswordForm'])) {
            $model->setAttributes($_POST['ChangePasswordForm']);

            if($model->changePassword()) {
                $user->setFlash('success', 'You have  successfully changed your password!');

                $this->redirect(Yii::app()->request->url);
            }
        }
        $this->render('change_password', array('model' => $model));
    }
}
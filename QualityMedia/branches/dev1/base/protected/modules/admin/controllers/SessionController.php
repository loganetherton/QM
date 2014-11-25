<?php
/**
 * Session controller.
 * Handles login and logout actions.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SessionController extends AdminController
{
    /**
     * @var string $layout The default layout for the controller view.
     */
    public $layout = 'column1';

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Login action.
     */
    public function actionCreate()
    {
        $user = Yii::app()->getUser();

        if(!$user->getIsGuest()) {
            $user->setFlash('info', 'You are already logged in');

            $this->redirect(Yii::app()->getHomeUrl());
        }

        $model = new LoginForm;

        if(isset($_POST['LoginForm'])) {
            $model->setAttributes($_POST['LoginForm']);

            if($model->login()) {
                $user->setFlash('success', 'You have been successfully logged in');

                $this->redirect(Yii::app()->getUser()->getReturnUrl());
            }
        }

        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * Logout action.
     */
    public function actionDelete()
    {
        $user = Yii::app()->getUser();

        // One has to be logged in first.
        if($user->getIsGuest()) {
            $user->setFlash('info', 'You have to login first');

            $this->redirect(array('session/create'));
        }

        $user->logout($destroySession = false);

        $this->redirect(array('session/create'));
    }
}
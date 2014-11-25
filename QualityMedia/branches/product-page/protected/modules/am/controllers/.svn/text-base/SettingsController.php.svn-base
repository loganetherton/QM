<?php
/**
 * Account manager settings controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SettingsController extends AmController
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'update';

    /**
     * @var string $formId Ajax form id - performAjaxValidation method uses this variable.
     */
    protected $formId = 'password-form';

    /**
     * Update settings.
     */
    public function actionUpdate()
    {
        $user = Yii::app()->getUser();

        $model = $this->loadModel($user->getId(), 'PasswordForm');

        $this->performAjaxValidation($model);

        if(isset($_POST['PasswordForm'])) {
            $model->setAttributes($_POST['PasswordForm']);

            if($model->save()) {
                $user->setFlash('success', 'You have successfully changed your password.');

                $this->redirect(array('settings/update'));
            }
        }

        $model->username = $user->getName();

        $this->render('update', array(
            'model'=>$model,
        ));
    }
}
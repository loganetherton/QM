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
}
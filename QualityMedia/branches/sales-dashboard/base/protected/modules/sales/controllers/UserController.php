<?php
/**
 * User controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UserController extends SalesController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();

        if(isset($_GET['User'])) {
            $model->setAttributes($_GET['User']);
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
        $model = $this->loadModel($id, 'User');

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
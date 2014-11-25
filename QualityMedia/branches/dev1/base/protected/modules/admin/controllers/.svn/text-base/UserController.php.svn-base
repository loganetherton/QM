<?php
/**
 * User management controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class UserController extends AdminController
{
    /**
     * List all models.
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();

        if(isset($_GET['User'])) {
            $model->setAttributes($_GET['User']);
        }

        $model->withSalesman();

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
        $model = $this->loadModel($id, 'User');

        $model->withSalesman();

        $this->render('view', array(
            'model'=>$model,
        ));
    }
}
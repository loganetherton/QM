<?php
/**
 * AdminManagement controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class AdminManagementController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Admin('search');
        $model->unsetAttributes();

        if(isset($_GET['Admin'])) {
            $model->setAttributes($_GET['Admin']);
        }

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
        $model = $this->loadModel($id, 'Admin');

        $this->render('view', array(
            'model'=>$model,
        ));
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new CreateAdminForm;

        if(isset($_POST['CreateAdminForm'])) {
            $model->setAttributes($_POST['CreateAdminForm']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Administrator has been created successfully');

                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * Update action.
     * @param integer $id Model id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'CreateAdminForm');

        if(isset($_POST['CreateAdminForm'])) {
            $model->setAttributes($_POST['CreateAdminForm']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Administrator has been successfully updated');
                $this->redirect(array('index'));
            }
        }
        $model->setAttributes(array('password' => '', 'verifyPassword'=>''));

        $this->render('update', array(
            'model'=>$model,
        ));
    }
}
<?php
/**
 * Client management controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientController extends AdminController
{
    /**
     * @var string $formId Ajax form id - performAjaxValidation method uses this variable.
     */
    protected $formId = 'client-form';

    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new AdminClient('search');
        $model->unsetAttributes();

        if(isset($_GET['AdminClient'])) {
            $model->setAttributes($_GET['AdminClient']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new ClientForm;
        $model->client = new AdminClient;
        $model->client->profile = new Profile;
        $model->client->billingInfo = new BillingInfo;

        // $this->performAjaxValidation($model);

        if(isset($_POST['AdminClient'], $_POST['Profile'], $_POST['BillingInfo'])) {
            $model->client->setAttributes($_POST['AdminClient']);
            $model->client->profile->setAttributes($_POST['Profile']);
            $model->client->billingInfo->setAttributes($_POST['BillingInfo']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Client has been created successfully');

                $this->redirect(array('client/index'));
            }
        }

        $this->render('create', array(
            'model'=>$model,
        ));
    }

    /**
     * Update action.
     * @param integer $id Resource id
     */
    public function actionUpdate($id)
    {
        $model = new ClientForm;
        $model->client = $this->loadModel($id, 'AdminClient');

        if($model->client->profile === null) {
            $model->client->profile = new Profile;
        }

        if($model->client->billingInfo === null) {
            $model->client->billingInfo = new BillingInfo;
        }

        if(isset($_POST['AdminClient'], $_POST['Profile'], $_POST['BillingInfo'])) {
            $model->client->setAttributes($_POST['AdminClient']);
            $model->client->profile->setAttributes($_POST['Profile']);
            $model->client->billingInfo->setAttributes($_POST['BillingInfo']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Client has been updated successfully');

                $this->redirect(array('client/index'));
            }
        }

        /*Linked*/
        $salesman = null;
        $accountManager = null;

        if($model->client->salesmanId !== null) {
            $salesman = $this->loadModel($model->client->salesmanId, 'Salesman');
        }

        if($model->client->accountManagerId !== null) {
            $accountManager = $this->loadModel($model->client->accountManagerId, 'AccountManager');
        }

        $this->render('create', array(
            'model'=>$model,
            'salesman' => $salesman,
            'accountManager' => $accountManager,
        ));
    }
}
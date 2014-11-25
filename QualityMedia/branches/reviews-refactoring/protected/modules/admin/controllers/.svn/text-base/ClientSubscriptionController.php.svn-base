<?php
/**
 * Client Subscription controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class ClientSubscriptionController extends AdminController
{
    /**
     * Choose as subscription plan action.
     */
    public function actionIndex($id)
    {
        $model = new Plan('search');
        $model->unsetAttributes();

        if(isset($_GET['Plan'])) {
            $model->setAttributes($_GET['Plan']);
        }

        $this->render('index', array(
            'model'=>$model,
            'clientId' => $id
        ));
    }

    /**
     * Create a subscription for a client
     * @param  int $planId subscription plan id
     * @param  int $clientId client id
     */
    public function actionCreate($clientId)
    {
        $plan   = new Plan;
        $client = $this->loadModel((int) $clientId, 'User');
        $salesmen = new Salesman;
        $salesman = new Salesman;


        $clientHasSalesman = false;
        if($client->salesmanId !== null) {
            $salesman->id = $client->salesmanId;
            $clientHasSalesman = true;
        }

        $model = new SubscriptionForm;

        if(isset($_POST['SubscriptionForm'])) {

            $attributes = $_POST['SubscriptionForm'];
            $attributes['clientId'] = $clientId;
            $model->setAttributes($attributes);

            if(isset($_POST['Plan']['id']) && (int)$_POST['Plan']['id']) {
                $plan = $this->loadModel((int)$_POST['Plan']['id'], 'Plan');
            }

            $model->planCode    = $plan->planCode;
            $model->currency    = 'USD';
            $model->accountCode = $client->accountCode;
            $model->email       = $client->email;
            $model->firstName   = $client->billingInfo->firstName;
            $model->lastName    = $client->billingInfo->lastName;

            $salesmanId = null;

            if(isset($_POST['User']['salesmanId']) && !empty($_POST['User']['salesmanId'])) {
                $salesmanId = (int) $_POST['User']['salesmanId'];
                $client->salesmanId = $salesmanId;
            }

            if($model->save()) {
                if($salesmanId) {
                    $client->save();
                }
                Yii::app()->getUser()->setFlash('success', 'Subscription has been created');
                $this->redirect(array('subscription/index'));
            }
        }

        $this->render('create', array(
            'model'=>$model,
            'client'=>$client,
            'salesman'=> $salesman,
            'salesmen'=> $salesmen,
            'plan'=>$plan,
            'clientHasSalesman' => $clientHasSalesman
        ));
    }
}
<?php
/**
 * Subscriptions controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SubscriptionController extends AdminController
{
    /**
     * List all models.
     */
    public function actionIndex()
    {
        $model = new Subscription('search');
        $model->unsetAttributes();

        if(isset($_GET['Subscription'])) {
            $model->setAttributes($_GET['Subscription']);
        }

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
        $model = $this->loadModel($id, 'Subscription');

        $this->render('view', array(
            'model'=>$model,
        ));
    }

   /**
     * Creates a subscription
     */
    public function actionCreate($clientId = null)
    {
        $model = new PlanForm;
        $client = null;

        if($clientId) {
            $client = $this->loadModel((int) $clientId, 'User');

            $model->setAttributes(
                array(
                    'businessName' => $client->billingInfo->companyName,
                    'firstName'    => $client->billingInfo->firstName,
                    'lastName'     => $client->billingInfo->lastName,
                    'email'        => $client->email,
                    'clientId'     => $clientId
                )
            );
        }

        $this->render('create', array(
            'model'=>$model,
            'client' => $client
        ));
    }

    /**
     * return plan by pricing criteria, if not found, return null
     * @return [type] [description]
     */
    public function actionAjaxFindPlanByPricing()
    {
        $post = $_POST;
        $result = false;
        $model = new PlanForm;

        $amount = false;
        $setupFee = null;

        if(isset($post['PlanForm']['amount'])) {
            $amount   = $post['PlanForm']['amount'];
        }

        if(isset($post['PlanForm']['setupFee'])) {
            $setupFee = $post['PlanForm']['setupFee'];
        }

        $errors = array();

        if((isset($post['PlanForm']['setupFee']) && !is_numeric($post['PlanForm']['setupFee'])) || !is_numeric($post['PlanForm']['amount'])) {
            array_push($errors, 'Amount and Setup Fee must be numbers');
        }

        if(!$amount) {
            //return plan name
            array_push($errors, 'Amount is required');

        }

        if(count($errors)) {
            header('HTTP/1.1 500 Internal Server Error');
            $result = implode('. ', $errors).'.';
        }
        else {
            $result = $model->createPlanName($amount, $setupFee);
        }

        echo $result;
    }

    /**
     * Save subscription
     * @return [type] [description]
     */
    public function actionAjaxSaveSubscription()
    {
        ob_start();
        $post = $_POST;
        $result = 'ok';

        $model = new PlanForm;

        if(isset($post['Salesman']['id']) && (int)$post['Salesman']['id']) {
            $post['PlanForm']['salesmanId'] = $post['Salesman']['id'];
        }

        $model->setAttributes($post['PlanForm']);

        if($model->save()) {
          echo 'ok';
        }
        else {
          Yii::app()->session['saveErrors'] = $model->getError('recurlyError');
          $this->redirect(array('ajaxShowErrors'));
        }
        ob_flush();
    }

    /**
     * Show errors
     */
    public function actionAjaxShowErrors()
    {
       header('HTTP/1.1 500 Internal Server Error');
       $errorsList = Yii::app()->session['saveErrors'];
       $errorsList = array_unique(array_map('strtolower',explode(', ',$errorsList)));

       echo trim(implode('. ', array_map('ucfirst', $errorsList)), '.').'.';
    }

}
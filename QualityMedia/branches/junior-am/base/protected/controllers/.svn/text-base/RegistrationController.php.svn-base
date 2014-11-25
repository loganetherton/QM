<?php
/**
 * Registration controller.
 *
 * @author Jakub <jakub@qualitymedia.com>
 */
class RegistrationController extends Controller
{
    /**
     * @var string $layout the default layout for the controller view.
     */
    public $layout = false;

    /**
     * Registration form.
     * @param string $plan Plan code
     * @param string $salesman Salesman username
     */
    public function actionCreate($plan = null)
    {
        if($plan !== null) {
            $plan = Plan::model()->findByPlanCode($plan);

            if($plan === null) {
                throw new CHttpException(404, 'Not found');
            }
        }

        $salesman = null;
        if(isset($_GET['salesman'])) {
            $salesman = Salesman::model()->findByUsername($_GET['salesman']);

            if($salesman === null) {
                throw new CHttpException(404, 'Not found');
            }
        }

        $accountManager = null;
        if(isset($_GET['accountManager'])) {
            $accountManager = AccountManager::model()->findByUsername($_GET['accountManager']);

            if($accountManager === null) {
                throw new CHttpException(404, 'Not found');
            }
        }

        $this->render('create', array(
            'plan'=>$plan,
            'salesman'=>$salesman,
            'accountManager'=>$accountManager,
        ));
    }

    /**
     * Products Pricing Tables
     * @return void
     */
    public function actionChoose()
    {
        $model = new ProductsForm;

        $this->render('choose', array(
                'model' => $model
            )
        );
    }

    /**
     * Order Form
     * @return void
     */
    public function actionOrder($planCode = 'value')
    {
        $model = new ProductsForm;

        $plans = $model->getPlans();
        $plan = null;

        $addons = null;

        if(isset($_POST['ProductsForm'])) {
            $postData = $_POST['ProductsForm'];

            if(isset($postData['plan']) && !empty($postData['plan'])) {
                $planCode = $postData['plan'];
            }

            if(isset($postData['addons']) && is_array($postData['addons'])) {
                $addons = $postData['addons'];
            }
        }

        if(in_array($planCode, array_keys($plans))) {
            $plan = $plans[$planCode];
        }
        else {
            throw new CHttpException(404, 'Not found');
        }

        $this->render('order', array(
                'model' => $model,
                'plans'  => $plans,
                'plan'  => $plan,
                'addons' => $addons
            )
        );
    }

    /**
     * Custom validation function for the site js forms
     * @return void
     */
    public function actionValidate()
    {
        $result = array(
            'status' => 'success',
        );

        if(isset($_POST['email'])) {
            $result['msg'] = $_POST['email'];

            $user = User::model()->findByEmail($_POST['email']);

            if($user !== null) {
                $result = array_merge(array(
                        'status' => 'error',
                        'errors' => array('Email is already taken')
                    )
                );
            }

        }

        echo CJSON::encode($result);
    }

    /**
     * Success page.
     */
    public function actionSuccess()
    {
        $this->render('success');
    }
}
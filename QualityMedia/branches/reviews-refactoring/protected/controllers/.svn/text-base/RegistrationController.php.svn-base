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
     * Success page.
     */
    public function actionSuccess()
    {
        $this->render('success');
    }
}
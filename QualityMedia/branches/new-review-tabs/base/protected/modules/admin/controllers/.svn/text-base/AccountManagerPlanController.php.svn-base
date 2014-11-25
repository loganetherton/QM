<?php
/**
 * Account manager custom subscription form.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AccountManagerPlanController extends AdminController
{
    /**
     * @var object AccountManager Account Manager object.
     */
    protected $accountManager;

    /**
     * Display available plans.
     * @param integer $id Account manager id
     */
    public function actionIndex($id)
    {
        $this->accountManager = $this->loadModel($id, 'AccountManager');

        $model = new Plan('search');
        $model->unsetAttributes();

        if(isset($_GET['Plan'])) {
            $model->setAttributes($_GET['Plan']);
        }

        $this->render('index', array(
            'model'=>$model,
            'accountManager'=>$this->accountManager,
        ));
    }

    /**
     * Returns link to account manager custom registration form.
     * @param object $data Model instance
     * @return string Absolute URL to account manager custom registration form
     */
    protected function getCustomFormLink($data)
    {
        return $this->createAbsoluteUrl('/registration/create', array('plan'=>$data->planCode, 'accountManager'=>$this->accountManager->username));
    }
}
<?php
/**
 * Employees controller.
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */

class ContractController extends CrmController
{
    /**
     * @var string $currentTab Current tab.
     */
    protected $currentTab;

    /**
     * Shows a list of saved contracts
     *
     * @access public
     * @return void
     * @throws CException
     */
    public function actionShow()
    {
        $user = Yii::app()->user;
        
//dd($user->getRole('admin'));
        //@todo: Skipping this for now since action role heriarchy isn't properly defined yet
        if (!$user->checkAccess('viewSavedContracts') && false) {
            throw new CException('You cannot view contracts');
        }
//dd($user->checkAccess('viewSavedContracts'));
        $model = new Contract('search');
        if ($user->isAssigned(UserIdentity::ROLE_ADMIN)) {
            $model->adminId = $user->getRoleId(UserIdentity::ROLE_ADMIN);
        }
        if ($user->isAssigned(UserIdentity::ROLE_SALESMAN)) {
            $model->salesmanId = $user->getRoleId(UserIdentity::ROLE_SALESMAN);
        }
        if ($user->isAssigned(UserIdentity::ROLE_AM)) {
            $model->accountManagerId = $user->getRoleId(UserIdentity::ROLE_AM);
        }

        if (isset($_POST['Contract'])) {
            $model->setAttributes($_POST['Contract']);
        }

        $this->render('saved', array(
            'model' => $model,
            'user' => $user,
        ));
    }

    /**
     * Create new contract
     *
     * @access public
     * @return void
     * @throws CException
     */
    public function actionCreate()
    {
        //dd(Yii::app()->user);
        if (Yii::app()->request->isAjaxRequest) {
            
            $socialNetwork = new ContractSocialNetwork;
            
            $ajaxReturn = $this->renderPartial('socialNetworkLogin', array(
                'model' => $socialNetwork,
            ), true);
            
            echo $ajaxReturn;
            
            Yii::app()->end();
        }
        //@todo: Skipping this for now since action role hierarchy isn't defined yet
        if (!Yii::app()->getUser()->checkAccess('createNewContract') && false) {
            throw new CException('You cannot create new contracts');
        }

        $model = new Contract;
        $contractSocialNetwork = ContractSocialNetwork::model();

        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            if (isset($_POST['Contract'])) {
                $model->attributes = $_POST['Contract'];

                $user = Yii::app()->getUser();
                if ($user->isAssigned(UserIdentity::ROLE_SALESMAN)) {
                    $model->salesmanId = $user->getRoleId(UserIdentity::ROLE_SALESMAN);
                }
                if ($user->isAssigned(UserIdentity::ROLE_ADMIN)) {
                    $model->adminId = $user->getRoleId(UserIdentity::ROLE_ADMIN);
                }
                if ($user->isAssigned(UserIdentity::ROLE_AM)) {
                    $model->accountManagerId = $user->getRoleId(UserIdentity::ROLE_AM);
                }

                if ($model->save()) {
                    $networks = isset($_POST['SocialNetworks']) ? (array) $_POST['SocialNetworks'] : array();
                    foreach ($networks as $network) {
                        $networkModel = new ContractSocialNetwork;
                        $networkModel->setAttributes($network);
                        $networkModel->contractId = $model->id;

                        if (!$networkModel->save()) {
                            throw new CException('Failed to save Social Network');
                        }
                    }
                }

                $transaction->commit();
            }
        }
        catch (CException $e) {
            $transaction->rollback();
            Yii::log('Could not create contract: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
        }
        
        $this->render('create', array(
            'model' => $model,
            'contractSocialNetwork' => $contractSocialNetwork,
            'socialNetworks' => !empty($_POST['SocialNetworks']) ? (array) $_POST['SocialNetworks'] : array(),
            'errors' => isset($model->errors) ? $model->errors: null,
        ));
    }
    
    public function actionAjax()
    {
        // We can also make the ajax call a separate action, which we can use filters to prevent
        // unncessary calls to
        
        $this->render('create', array(
            'data' => $data,
        ));
    }
    
    public function filters()
    {
        return array(
            'ajaxOnly + ajax',
            'accessControl',
        );
    }
}
<?php
/**
 * AccountManager controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class AccountManagerController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new AccountManager('search');
        $model->unsetAttributes();

        if(isset($_GET['AccountManager'])) {
            $model->setAttributes($_GET['AccountManager']);
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
        $model = $this->loadModel($id, 'AccountManager');
        $userModel = new User('search');

        $this->render('view', array(
            'model'=>$model,
            'userModel'=> $userModel
        ));
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new CreateAccountManagerForm;
        $userModel = new User('search');

        // Set model for quickSearch
        $searchModel = new User('search');

        if(isset($_GET['clientsToLink'])) {
            $searchModel->setAttributes($_GET['clientsToLink']);
        }

        if(isset($_POST['CreateAccountManagerForm'])) {
            $model->setAttributes($_POST['CreateAccountManagerForm']);

            if($model->save()) {

                //link clients if they have been assigned
                $linkedClientsIds = Yii::app()->session['newAccountLinkedClients'];

                if(is_array($linkedClientsIds) && count($linkedClientsIds)) {
                    $accountManagerId = Yii::app()->db->getLastInsertID();

                    foreach($linkedClientsIds as $id) {
                        $user = $this->loadModel($id, 'User');
                        $user->accountManagerId = $accountManagerId;
                        $user->save();
                    }
                    unset(Yii::app()->session['newAccountLinkedClients']);
                }

                Yii::app()->getUser()->setFlash('success', 'Account Manager has been created successfully');

                $this->redirect(array('index'));
            }
        }

        if(!isset($_GET['ajax'])) {
            unset(Yii::app()->session['newAccountLinkedClients']);
        }

        $this->render('create', array(
            'model'=>$model,
            'userModel'=> $userModel,
            'searchModel' => $searchModel
        ));
    }

    /**
     * Update action.
     * @param integer $id Model id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'CreateAccountManagerForm');
        $userModel = new User('search');

        // Set model for quickSearch
        $searchModel = new User('search');

        if(isset($_GET['clientsToLink'])) {
            $searchModel->setAttributes($_GET['clientsToLink']);
        }

        if(isset($_POST['CreateAccountManagerForm'])) {
            $model->setAttributes($_POST['CreateAccountManagerForm']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Account Manager has been successfully updated');
                $this->redirect(array('index'));
            }
            else {
                $model->setAttributes(array('password' => '', 'verifyPassword'=>''));
            }
        }

        $this->render('update', array(
            'model'=>$model,
            'userModel'=> $userModel,
            'searchModel' => $searchModel,

        ));
    }

    /**
     * Ajax action to unlink the client from the Account Manager
     */
    public function actionAjaxUnlinkClient($id, $mode = 'update')
    {
        if($mode == 'create') {
            $usersIds = (array) Yii::app()->session['newAccountLinkedClients'];

            $idKey = array_search($id, $usersIds);

            if($idKey !== null) {
                unset($usersIds[$idKey]);
                Yii::app()->session['newAccountLinkedClients'] = $usersIds;
            }
        }
        else {
            $model = $this->loadModel($id, 'User');
            $model->accountManagerId = NULL;

            if($model->save()) {
                echo 'ok';
            }
            else {
                'error';
            }
        }

    }

    /**
     * Ajax action to link clients to the specified the Account Manager
     */
    public function actionAjaxLinkClients()
    {
        $request = Yii::app()->getRequest();

        if($request->getIsPostRequest()) {

            if(isset($_POST['ids'])) {
                $ids = $_POST['ids'];
            }

            $accountManagerId = null;
            if(isset($_POST['accountManagerId'])) {
                $accountManagerId = (int) $_POST['accountManagerId'];
            }

            if (empty($ids)) {
                echo CJSON::encode(array('status' => 'failure', 'msg' => 'accountManagerId or user Ids missing'));
                die();
            }

            $savedData = array();

            //If accountManagerId is given, do rows update, if not, store them to a session to save after a new account manager creation
            if($accountManagerId) {

                foreach ($ids as $id) {
                    $model = $this->loadModel($id, 'User');
                    $model->accountManagerId = $accountManagerId;

                    if($model->save()) {
                        array_push($savedData, array($id => 'saved'));
                    }
                    else {
                        array_push($savedData, array($id => $model->getErrors()));
                    }
                }
            }
            else {
                Yii::app()->session['newAccountLinkedClients'] = $ids;
            }

            echo CJSON::encode(array('status' => 'success', 'savedData' => $savedData));
        }
    }

    /**
     * Returns search results in json format
     * @param  string $user requsted phrase
     */
    public function actionAjaxSearchUser($user='dawid')
    {
        $model = new User('Search');

        $criteria = new CDbCriteria;
        $criteria->select = array('id', 'email');
        $criteria->addSearchCondition('email', $user);

        $users = $model->findAll($criteria);
        $result = array();

        foreach($users as $user) {
            array_push($result, array($user->id => $user->email));
        }
        echo CJSON::encode($users);
    }

}
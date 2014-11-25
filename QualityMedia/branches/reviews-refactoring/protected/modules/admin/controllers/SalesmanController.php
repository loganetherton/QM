<?php
/**
 * Salesman controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SalesmanController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Salesman('search');
        $model->unsetAttributes();

        if(isset($_GET['Salesman'])) {
            $model->setAttributes($_GET['Salesman']);
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
        $model = $this->loadModel($id, 'Salesman');

        //get subscriptions linked to the Salesman
        $subscriptionModel = new Subscription('search');

        $this->render('view', array(
            'model'=>$model,
            'subscriptionModel' => $subscriptionModel
        ));
    }

    /**
     * Create action.
     */
    public function actionCreate()
    {
        $model = new CreateSalesmanForm;

        if(isset($_POST['CreateSalesmanForm'])) {
            $model->setAttributes($_POST['CreateSalesmanForm']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Sales Rep. has been created successfully');
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
        $model = $this->loadModel($id, 'CreateSalesmanForm');

        if(isset($_POST['CreateSalesmanForm'])) {
            $model->setAttributes($_POST['CreateSalesmanForm']);

            if($model->save()) {
                Yii::app()->getUser()->setFlash('success', 'Sales Rep. has been successfully updated');
                $this->redirect(array('index'));
            }
            else {
                $model->setAttributes(array('password' => '', 'verifyPassword'=>''));
            }
        }

        $this->render('update', array(
            'model'=>$model
        ));
    }

    /**
     * Clears the SalesmanId field in the user row
     * @param  int $userId [description]
     * @return [type]         [description]
     */
    public function actionUnlinkClient()
    {
        $userId = $_GET['id'];
        $userId = (int) $userId;

        //@TODO not safe - make a security fix
        if($userId) {
            $model = $this->loadModel($userId, 'User');

            $model->salesmanId = null;
            $model->save();
            $this->redirect(array('index'));
        }
    }

    /**
     * Ajax action to unlink the client from the Sales Rep
     */
    public function actionAjaxUnlinkClient($id, $mode = 'update')
    {
        $id = (int) $id;
        if($mode == 'create') {
            $usersIds = (array) Yii::app()->session['newSalesmanLinkedClients'];

            $idKey = array_search($id, $usersIds);

            if($idKey !== null) {
                unset($usersIds[$idKey]);
                Yii::app()->session['newSalesmanLinkedClients'] = $usersIds;
            }
        }
        else {
            $model = $this->loadModel($id, 'User');

            $model->salesmanId = NULL;

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

            if(isset($_POST['ids'])){
                $ids = $_POST['ids'];
            }

            $salesmanId = (int) $_POST['salesmanId'];

            if (empty($ids)) {
                echo CJSON::encode(array('status' => 'failure', 'msg' => 'salesmanId or user Ids missing'));
                die();
            }

            $savedData = array();

            //If salesmanId is given, do rows update, if not, store them to a session to save after a new account manager creation
            if($salesmanId) {
                foreach ($ids as $id) {
                    $model = $this->loadModel($id, 'User');
                    $model->salesmanId = $salesmanId;

                    if($model->save()) {
                        array_push($savedData, array($id => 'saved'));
                    }
                    else {
                        array_push($savedData, array($id => $model->getErrors()));
                    }
                }
            }
            else {
                Yii::app()->session['newSalesmanLinkedClients'] = $ids;
                $savedData = $ids;
            }

            echo CJSON::encode(array('status' => 'success', 'savedData' => $savedData));
        }
    }

}
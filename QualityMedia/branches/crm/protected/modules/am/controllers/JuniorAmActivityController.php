<?php
/**
 * Controller for viewing Junior Manager actions history
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */

class JuniorAmActivityController extends AmController
{
    public $actionTypesLabels;
    public $statusIcons = array('open', 'waiting', 'approved', 'nonapproved');

    public function actionIndex($id = null)
    {
        if(isset($_GET['AmActivity']['accountManagerId']) && $_GET['AmActivity']['accountManagerId']) {
            $id = (int) $_GET['AmActivity']['accountManagerId'];
        }

        $model = new AmActivity;
        $model->accountManagerId = $id;

        if(isset($_GET['AmActivity'])) {
            $model->setAttributes(array(
                    'accountManagerId' => $_GET['AmActivity']['accountManagerId'],
                    'businessId' => $_GET['AmActivity']['businessId']
                )
            );
        }

        if(isset($_GET['AmActivity']['dateRange']) && $_GET['AmActivity']['dateRange']) {
            $model->dateRange = $_GET['AmActivity']['dateRange'];
        }

        $data = $model->search();

        $this->render('index', array(
            'model'=>$model,
            'data'=> $data,
            'id'=>$id,
        ));
    }

    public function actionAjaxGetClients($id)
    {
        $clients = $model = $this->loadModel($id, 'AccountManager')->clientsDropDownList();
        $result = array();

        foreach($clients as $clientId => $client) {
            $result[] = array('id' => $clientId, 'name' => $client);
        }

        echo CJSON::encode($result);
    }
}
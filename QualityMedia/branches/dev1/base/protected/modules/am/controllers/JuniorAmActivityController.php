<?php
/**
 * Controller for viewing Junior Manager actions history
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */

class JuniorAmActivityController extends AmController
{
    public $actionTypesLabels;
    public $statusIcons = array('open', 'waiting', 'approved', 'nonapproved', 'reverted');

    /**
     * Index action
     */
    public function actionIndex($id = null)
    {
        $loggedUser = Yii::app()->getUser();

        if(!$loggedUser->isSenior()) {
            throw new CHttpException(403, 'Forbidden Access. Please do not repeat this request again.');
        }

        // If the logged AM is senior and has no juniors assigned to, show the no-result page
        $assignedAms = $loggedUser->getUser()->linkedAmsDropDownList('fullName');

        if(count($assignedAms) == 0) {
            $this->render('index_noJrs');
            return;
        }

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

    /**
     * Summary stats action.
     */
    public function actionSummary()
    {
        $loggedUser = Yii::app()->getUser();

        if(!$loggedUser->isSuperSenior()) {
            throw new CHttpException(403, 'Forbidden Access. Please do not repeat this request again.');
        }

        $model = new AmStats('search');
        $model->unsetAttributes();

        //handle review type filter
        if(isset($_GET['AmStats']['reviewTypeFilter'])) {
            $model->reviewTypeFilter = $_GET['AmStats']['reviewTypeFilter'];
            //Apply review type filters
            switch($model->reviewTypeFilter) {
                case AmStats::REVIEW_TYPE_FILTER_PRECONTRACT :
                    $model->fullPrecontract();
                break;
                case AmStats::REVIEW_TYPE_FILTER_POSTCONTRACT :
                    $model->postcontract();
                break;
                case AmStats::REVIEW_TYPE_FILTER_ALL :
                default:
                break;
            }
        }

        if(isset($_GET['AmStats']['dateRange']) && $_GET['AmStats']['dateRange']) {
            $model->dateRange = $_GET['AmStats']['dateRange'];
        }

        if(isset($_GET['AmStats'])) {
            $model->setAttributes($_GET['AmStats']);
        }

        $this->render('summary', array(
            'model'=>$model,
        ));
    }
}
<?php
/**
 * Review controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ReviewController extends AmController
{
    /**
     * @var string $currentTab Current tab.
     */
    protected $currentTab;

    /**
     * Index action.
     * @param string $tab Current tab
     */
    public function actionIndex($tab = null)
    {
        $this->executeAction($tab, Yii::app()->getUser()->getId(), 'index', false);
    }

    /**
     * Shows reviews linked to Junior AM.
     */
    public function actionJr($id, $tab = null)
    {
        $this->executeAction($tab, $id, 'jr', true);
    }

    /**
     * Mark review as read.
     * @param integer $id Review id
     * @throws CHttpException
     */
    public function actionMarkAsRead($id)
    {
        if(!Yii::app()->getRequest()->getIsPostRequest()) {
            throw new CHttpException(400, 'Bad Request');
        }

        $model = $this->loadModel($id, 'AmReview');

        $model->markAsProcessed();

        echo CJSON::encode(array(
            'reviews'   => Yii::app()->getUser()->getReviewsCount(),
            'messages'  => Yii::app()->getUser()->getMessagesCount(),
        ));

        Yii::app()->end();
    }

    /**
     * Execute index/jr action.
     * @param string $tab Current tab
     * @param integer $accountManagerId Account manager id
     * @param string $view View name
     * @param boolean $isJuniorMode Is Junior modes
     */
    protected function executeAction($tab, $accountManagerId, $view, $isJunior = false)
    {
        $accountManager = $this->loadModel($accountManagerId, 'AccountManager');

        if($isJunior) {
            $this->setJrMode($accountManager);
        }

        $model = new AmReview('search');
        $model->unsetAttributes();

        if(isset($_GET['AmReview'])) {
            $model->setAttributes($_GET['AmReview']);
        }

        if($accountManager->showOnlyLinkedFeeds == 1) {
            $model->businessScope($accountManager->getClientIds());
        }

        // Reviews tabs
        $this->currentTab = $tab;

        switch($tab) {
            case 'followup':
                $model->followup();
                break;
            case 'precontract':
                $model->opened()->precontract();
                break;
            case 'filtered':
                $model->opened()->notPrecontract()->filtered();
                break;
            case 'archived':
                $model->archived();
                break;
            case 'flagged':
                $model->flagged();
                break;
            case 'replied':
                $model->replied();
                break;
            case 'opened':
            default:
                // In case invalid tab has been specified
                $this->currentTab = 'opened';
                $model->opened()->notPrecontract()->notFiltered();
                break;
        }

        // Don't display deleted reviews
        $model->notDeleted();

        $this->render($view, array(
            'model'=>$model,
            'accountManager'=>$accountManager,
        ));
    }
}
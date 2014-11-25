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
        $accountManager = $this->loadModel(Yii::app()->getUser()->getId(), 'AccountManager');

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
                $model->opened();
                break;
        }

        $this->render('index', array(
            'model'=>$model,
        ));
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
}
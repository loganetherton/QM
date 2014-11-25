<?php
/**
 * Messages controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MessageController extends AmController
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

        // Messages tabs
        $this->currentTab = $tab;

        switch($tab) {
            case 'sent':
                $model->pmSent();
                break;
            case 'archived':
                $model->pmArchived();
                break;
            case 'filtered':
                $model->pmInbox()->filtered();
                break;
            case 'inbox':
            default:
                // In case invalid tab has been specified
                $this->currentTab = 'inbox';
                $model->pmInbox()->notFiltered();
                break;
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }
}
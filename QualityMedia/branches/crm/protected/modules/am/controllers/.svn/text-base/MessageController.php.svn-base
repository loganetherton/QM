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
        $this->executeAction($tab, Yii::app()->getUser()->getId(), 'index', false);
    }

    /**
     * Shows Linked Junior AM's private messages
     */
    public function actionJr($id, $tab = null)
    {
        $this->executeAction($tab, $id, 'jr', true);
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

        // Don't display deleted reviews
        $model->notDeleted();

        $this->render($view, array(
            'model'=>$model,
            'accountManager'=>$accountManager,
        ));
    }
}
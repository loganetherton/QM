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
        $viewData = $this->viewData($this->loadModel(Yii::app()->getUser()->getId(), 'AccountManager'), $tab);

        $this->render('index', $viewData);
    }

    /**
     * Shows Linked Junior AM's private messages
     */
    public function actionJr($id, $tab = null)
    {
        $accountManager = $this->loadModel($id, 'AccountManager');

        $viewData = $this->viewData($accountManager, $tab);
        $viewData['accountManager'] = $accountManager;

        $this->setJrMode($id);

        $this->render('jr', $viewData);
    }

    /**
     * Helper function for view data
     * @param  AccountManager $accountManager Account Manager Model
     * @param  string $tab Current tab
     * @return array data required in the view
     */
    private function viewData($accountManager, $tab = null)
    {
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

        return array(
            'model'=>$model
        );
    }
}
<?php
/**
 * Dashboard controller.
 * This is the default Account Manager module controller.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class DashboardController extends AmController
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * View dashboard action.
     */
    public function actionView()
    {
        $this->render('view');
    }

    /**
     * User system notifications action.
     */
    public function actionSystemNotifications()
    {
        $model = new SystemNotification;

        if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['id']) && !empty($_POST['id'])) {

            switch ($_POST['action']) {
                case 'markAsArchived':
                    $this->loadModel((int)$_POST['id'], 'SystemNotification')->markAsArchived();
                break;
            }
        }

        $this->layout = false;
        $this->renderPartial('systemNotifications', array(
            'model' => $model
        ));
    }

    public function actionGetUnreadNotifications()
    {
        Yii::app()->getUser()->setSystemNotificationsCount(0);

        $output = array('notifications' => 0);
        echo CJSON::encode($output);
    }
}
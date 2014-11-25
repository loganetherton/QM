<?php
/**
 * Recurly push notification listener.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PushNotificationController extends Controller
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'create';

    /**
     * Handle push notification.
     */
    public function actionCreate()
    {
        try {
            Yii::app()->getComponent('recurly')->handlePushNotification();
        }
        catch(Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'push');

            throw new CHttpException(500);
        }
    }
}
<?php
/**
 * Subscription state controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SubscriptionStateController extends AdminController
{
    /**
     * Update model.
     * @param integer $id Model id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'Subscription');

        if($model->cancelSubscription()) {
            Yii::app()->getUser()->setFlash('success', 'Subscription has been canceled');
        }
        else {
            Yii::app()->getUser()->setFlash('danger', 'Subscription has not been canceled');
        }

        $this->redirect(array('subscription/view', 'id'=>$model->id));
    }
}
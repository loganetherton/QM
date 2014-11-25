<?php
/**
 * Message answer controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MessageAnswerController extends AmController
{
    /**
     * @var object $model MessageThread.
     */
    protected $model;

    /**
     * Create action.
     * @param integer $id Message thread id
     */
    public function actionCreate($id)
    {
        if(!isset($_POST['Message']) || !isset($_POST['Message']['action'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $this->model = $this->loadModel($id, 'Review');

        switch(key($_POST['Message']['action'])) {
            case 'archive':
                $this->archiveMessage();
                break;
            case 'private':
                $this->answerMessage();
                break;
            default:
                throw new CHttpException(400, 'Invalid input');
                break;
        }

        $params = CJSON::decode($_POST['params']);
        unset($params['ajax']);

        $this->redirect(array_merge(array('message/index', '#'=>'message-'.$id), $params));
    }

    /**
     * Approve action.
     * @param integer $id Message thread id
     */
    public function actionApprove($id)
    {

        if(!isset($_POST['Message']) || !isset($_POST['Message']['action'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $this->model = $this->loadModel($id, 'Review');

        switch(key($_POST['Message']['action'])) {
            case 'private':
                $this->approvePrivateMessage();
                break;
            case 'privateWithChange':
                $this->updatePrivateMessage();
                $this->approvePrivateMessage(true);
                break;
            default:
                throw new CHttpException(400, 'Invalid input');
                break;
        }

        $params = CJSON::decode($_POST['params']);
        unset($params['ajax']);

        $this->redirect(array_merge(array('message/jr', '#'=>'message-'.$id), $params));
    }

    /**
     * Archive message.
     */
    protected function archiveMessage()
    {
        if($this->model->moveMessagesThreadToArchived()) {
            Yii::app()->getUser()->setFlash('success', 'Messages thread has been archived');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Message thread has not been archived');
        }
    }

    /**
     * Answer message.
     */
    public function answerMessage()
    {
        $review = $this->model;

        $model = new MessageAnswer;
        $model->setAttributes(array(
            'reviewId'          => $review->id,
            'from'              => Yii::app()->getUser()->getUser()->getFullName(),
            'userId'            => $review->userId,
            'messageContent'    => $_POST['Message']['answer'],
        ));

        $succesMessage = 'A private message to %s has been sent';
        $addToQueue    = true;

        //If the user is Jr, change the message and ommit queue adding action
        if(!Yii::app()->getUser()->isSenior()) {
            $succesMessage = 'Your action will be reviewed by your Senior Account Manager before it is posted.';
            $addToQueue    = false;
        }

        if($model->save(true, null, $addToQueue)) {
            Yii::app()->getUser()->setFlash('success', sprintf($succesMessage, $review->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message to %s has not been sent', $review->userName));
        }
    }

    /**
     * Answer with private message.
     */
    protected function updatePrivateMessage()
    {
        $review    = $this->model;
        $comment   = $_POST['Message']['answer'];
        $messageId = $_POST['Message']['messageId'];

        $model = $this->loadModel($messageId, 'Message');
        $model->messageContent = $comment;

        if($model->save()) {
            Yii::app()->getUser()->setFlash('success', sprintf('A private message to %s has been updated', $review->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message to %s has not been updated', $review->userName));
        }
    }

    /**
     * Approve private message
     * @return
     */
    protected function approvePrivateMessage($changed = false)
    {
        if(!isset($_POST['Message']['messageId'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $model = $this->loadModel($_POST['Message']['messageId'], 'Message');

        if($model->approvePrivateMessage($changed)) {
            Yii::app()->getUser()->setFlash('success', sprintf('A private message has been approved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message has not been approved', $this->model->userName));
        }
    }
}
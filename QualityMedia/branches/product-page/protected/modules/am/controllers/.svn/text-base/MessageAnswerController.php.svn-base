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
     * Archive message.
     */
    protected function archiveMessage()
    {
        if($this->model->moveMessagesThreadToArchived(true)) {
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

        if($model->save()) {
            Yii::app()->getUser()->setFlash('success', sprintf('A private message to %s has been sent', $review->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message to %s has not been sent', $review->userName));
        }
    }
}
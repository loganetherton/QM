<?php
/**
 * Review answer controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ReviewAnswerController extends AmController
{
    /**
     * @var object $model Review.
     */
    protected $model;

    /**
     * Create action
     * @param integer $id Review id
     */
    public function actionCreate($id)
    {
        if(!isset($_POST['Review']) || !isset($_POST['Review']['action'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $this->model = $this->loadModel($id, 'Review');

        switch(key($_POST['Review']['action'])) {
            case 'archive':
                $this->archiveReview();
                break;
            case 'public':
                $this->answerWithPublicMessage();
                break;
            case 'private':
                $this->answerWithPrivateMessage();
                break;
            case 'flag':
                $this->flagReview();
                break;
            case 'followUp':
                $this->moveToFollowUp();
                break;
            default:
                throw new CHttpException(400, 'Invalid input');
                break;
        }

        // It helps to redirect AM back to the page where he/she was
        $params = CJSON::decode($_POST['params']);
        unset($params['ajax']);

        $this->redirect(array_merge(array('review/index', '#'=>'review-'.$id), $params));
    }

    /**
     * Archive message.
     */
    protected function archiveReview()
    {
        if($this->model->markAsArchived()) {
            Yii::app()->getUser()->setFlash('success', 'Review has been archived');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Review has not been archived');
        }
    }

    /**
     * Answer with public message.
     */
    protected function answerWithPublicMessage()
    {
        $model = $this->model;

        $model->setAttributes(array(
            'publicCommentContent'  => $_POST['Review']['answer'],
            'publicCommentAuthor'   => Yii::app()->getUser()->getUser()->getFullName(),
            'publicCommentDate'     => date('Y-m-d H:i:s'),
            'lastActionAt'          => date('Y-m-d H:i:s'),
        ));

        // Move to replied tab (without saving the model)
        $model->markAsReplied();

        if($model->save()) {
            Yii::app()->getUser()->setFlash('success', sprintf('A public message has been posted', $model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A public message has not been posted', $model->userName));
        }
    }

    /**
     * Answer with private message.
     */
    protected function answerWithPrivateMessage()
    {
        $review = $this->model;

        $model = new MessageAnswer;
        $model->setAttributes(array(
            'reviewId'          => $review->id,
            'from'              => Yii::app()->getUser()->getUser()->getFullName(),
            'userId'            => $review->userId,
            'messageContent'    => $_POST['Review']['answer'],
        ));

        if($model->save()) {
            Yii::app()->getUser()->setFlash('success', sprintf('A private message to %s has been sent', $review->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message to %s has not been sent', $review->userName));
        }
    }

    /**
     * Flag review.
     */
    protected function flagReview()
    {
        if(!isset($_POST['Review']['flagReason']) || empty($_POST['Review']['flagReason'])) {
            throw new CHttpException(400, 'Invalid input');
        }

        if($this->model->markAsFlagged($_POST['Review']['flagReason'])) {
            Yii::app()->getUser()->setFlash('success', 'Review has been flagged');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Review has not been flagged');
        }
    }

    /**
     * Move review to Follow Up stack.
     */
    protected function moveToFollowUp()
    {
        if($this->model->moveToFollowUp()) {
            Yii::app()->getUser()->setFlash('success', 'Review has been moved to Follow Up stack');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Review has not been moved to Follow Up stack');
        }
    }
}
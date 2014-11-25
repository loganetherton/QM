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

    public function actionApprove($id)
    {
        if(!isset($_POST['Review']) || !isset($_POST['Review']['action'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $this->model = $this->loadModel($id, 'Review');

        switch(key($_POST['Review']['action'])) {
            case 'public':
                $this->approvePublicMessage();
                break;
            case 'publicWithChange':
                $this->answerWithPublicMessage();
                $this->approvePublicMessage(true);
                break;
            case 'privateWithChange':
                $this->updatePrivateMessage();
                $this->approvePrivateMessage(true);
                break;
            case 'private':
                $this->approvePrivateMessage();
                break;
            case 'flag':
                $this->approveFlag();
                break;
            case 'editFlag':
                $this->flagChange();
                break;
            case 'seniorAmNote':
                $this->addSeniorAmNote();
                break;
            default:
                throw new CHttpException(400, 'Invalid input');
                break;
        }

        // It helps to redirect AM back to the page where he/she was
        $params = CJSON::decode($_POST['params']);
        unset($params['ajax']);

        $this->redirect(array_merge(array('review/jr', '#'=>'review-'.$id), $params));
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

    public function addSeniorAmNote()
    {
        if(!isset($_POST['Review']['seniorAmNote'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $this->model->setAttributes(array(
            'seniorAmNote' => $_POST['Review']['seniorAmNote'],
            'seniorAmNoteUpdateDate' => date('Y-m-d H:i:s')
            )
        );

        if($this->model->save()) {
            Yii::app()->getUser()->setFlash('success', sprintf('A note has been saved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A note has not been saved', $this->model->userName));
        }
    }

    /**
     * Answer with public message.
     */
    protected function answerWithPublicMessage()
    {
        $comment = $_POST['Review']['answer'];
        $author  = Yii::app()->getUser()->getUser()->getFullName();

        $succesMessage = 'A public message has been posted';
        $addToQueue    = true;

        //If the user is Jr, change the message and ommit queue adding action
        if(!Yii::app()->getUser()->isSenior()) {
            $succesMessage = 'Your action will be reviewed by your Senior Account Manager before it is posted.';
            $addToQueue    = false;
        }

        if($this->model->answerWithPublicComment($comment, $author, $addToQueue)) {
            Yii::app()->getUser()->setFlash('success', sprintf($succesMessage, $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A public message has not been posted', $this->model->userName));
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
        $comment   = $_POST['Review']['answer'];
        $messageId = $_POST['Review']['messageId'];

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
     * Flag review.
     */
    protected function flagReview()
    {
        if(!isset($_POST['Review']['flagReason']) || empty($_POST['Review']['flagReason']) || empty($_POST['Review']['flagCategory'])) {
            throw new CHttpException(400, 'Invalid input');
        }

        $succesMessage = 'Review has been flagged';
        $addToQueue    = true;

        //If the user is Jr, change the message and ommit queue adding action
        if(!Yii::app()->getUser()->isSenior()) {
            $succesMessage = 'Your action will be reviewed by your Senior Account Manager before it is posted.';
            $addToQueue    = false;
        }

        if($this->model->markAsFlagged($_POST['Review']['flagReason'], $_POST['Review']['flagCategory'], $addToQueue)) {
            Yii::app()->getUser()->setFlash('success', $succesMessage);
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

    //Approval functions

    /**
     * Approve public message
     * @return
     */
    protected function approvePublicMessage($changed = false)
    {
        if($this->model->approvePublicMessage($changed )) {
            Yii::app()->getUser()->setFlash('success', sprintf('A public message has been approved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A public message has not been approved', $this->model->userName));
        }
    }

    /**
     * Approve private message
     * @return
     */
    protected function approvePrivateMessage($changed = false)
    {
        if(!isset($_POST['Review']['messageId'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $model = $this->loadModel($_POST['Review']['messageId'], 'Message');

        if($model->approvePrivateMessage($changed)) {
            Yii::app()->getUser()->setFlash('success', sprintf('A private message has been approved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A private message has not been approved', $this->model->userName));
        }
    }

    /**
     * Approve public flag
     * @return
     */
    protected function approveFlag($changed = false)
    {
        if($this->model->approveFlag($changed )) {
            Yii::app()->getUser()->setFlash('success', sprintf('A flag has been approved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A flag has not been approved', $this->model->userName));
        }
    }

    /**
     * Change Flag details
     * @return
     */
    protected function flagChange()
    {
        if(!isset($_POST['Review']['flagReason']) || empty($_POST['Review']['flagReason']) || empty($_POST['Review']['flagCategory'])) {
            throw new CHttpException(400, 'Invalid input');
        }

        if($this->model->markAsFlagged($_POST['Review']['flagReason'], $_POST['Review']['flagCategory'], true, true)) {
            Yii::app()->getUser()->setFlash('success', 'Flag has been updated');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Flag has been updated');
        }
    }

}
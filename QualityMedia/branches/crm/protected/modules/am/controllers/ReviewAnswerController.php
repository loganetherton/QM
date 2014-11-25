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

        $jrMode = (isset($_POST['jrMode']) && $_POST['jrMode'] == 1);

        $this->redirect(array_merge(array($jrMode ? 'review/jr' : 'review/index', '#'=>'review-'.$id), $params));
    }

    /**
     * Handles the approval actions made by Senior Am on Junior Am replies
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
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
                $this->answerWithPublicMessage(true, false);
                $this->approvePublicMessage(true);
                break;
            case 'privateWithChange':
                $this->updatePrivateMessage();
                $this->approvePrivateMessage(true);
                break;
            case 'setAsPublic':
                $this->convertPrivateMessageToPublic();
                break;
            case 'setAsPrivate':
                $this->convertPublicMessageToPrivate();
                break;
            case 'setAsPublicWithChange':
                $this->convertPrivateMessageToPublic(true);
                break;
            case 'setAsPrivateWithChange':
                $this->convertPublicMessageToPrivate(true);
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
     * Used to prevent overriding replies in case two Ams are trying to answer the review in the same time
     */
    public function actionAjaxCheckPermission()
    {
        if(!isset($_POST['action']) || empty($_POST['action']) || !isset($_POST['id']) || empty($_POST['id']) || !is_numeric($_POST['id'])) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $result = array('status' => 'success');

        $model = $this->loadModel($_POST['id'], 'Review');

        //False result means the reply is not replied yet. Otherwise, it should contain author(AM's) id
        $isReplied = false;

        switch($_POST['action']) {
            case 'public':
                $isReplied = $model->isRepliedPublicly();
            break;
            case 'private':
                $isReplied = $model->isRepliedPrivately();
            break;
        }

        if($isReplied) {
            $accountManager = $this->loadModel($isReplied, 'AccountManager');

            //If the logged user is junior, set allow override, otherwise deny the action
            if(Yii::app()->getUser()->isSenior() && !$accountManager->isSenior()) {
                $errorMessage = 'Already replied to by %s %s. Do you want to override the message?';
                $errorCode = 'repliedByJr';
            }
            else {
                $errorMessage = 'Private reply NOT posted, already replied to by %s % s (%s Manager)';
                $errorCode = 'notAllowed';
            }

            $message = sprintf(
                $errorMessage,
                $accountManager->firstName,
                $accountManager->lastName,
                $accountManager->getTypeLabel(false)
            );

            $result = array(
                'status' => 'error',
                'msg' => $message,
                'errorType' => $errorCode,
                'amType' => $accountManager->getTypeLabel(false)
            );

        }

        echo CJSON::encode($result);
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
     * @param  boolean $preserveAuthor use it to leave previous author if you only change the content
     */
    protected function answerWithPublicMessage($preserveAuthor = false, $addToQueue = true)
    {
        $comment = $_POST['Review']['answer'];
        $author  = Yii::app()->getUser()->getUser();

            //Allow overriding only by Sr AM by Juniors, deny in any other case
            $isReplied = $this->model->isRepliedPublicly();

            if($isReplied) {
                $accountManager = $this->loadModel($isReplied, 'AccountManager');

                if(!$author->isSenior() || $accountManager->isSenior()) {
                    $message = sprintf(
                        'Public comment NOT posted, already replied to by %s % s (%s Manager)',
                        $accountManager->firstName,
                        $accountManager->lastName,
                        $accountManager->getTypeLabel(false)
                    );

                    Yii::app()->getUser()->setFlash('error', $message);
                    return;
                }
        }

        $succesMessage = 'A public message has been posted';

        //If the user is Jr, change the message and ommit queue adding action
        if(!Yii::app()->getUser()->isSenior()) {
            $succesMessage = 'Your action will be reviewed by your Senior Account Manager before it is posted.';
            $addToQueue    = false;
        }

        $commentAuthor = $author;

        //Leave author untouched
        if($preserveAuthor && $this->model->accountManagerId && AccountManager::model()->findByPk($this->model->accountManagerId)) {
            $commentAuthor = AccountManager::model()->findByPk($this->model->accountManagerId);
        }

        if($this->model->answerWithPublicComment($comment, $commentAuthor, $addToQueue)) {

            //Add activity record
            $activityModel = new AmActivity;
            $activityModel->addActivity(
                $author->id,
                $this->model->businessId,
                $this->model->id,
                AmActivity::TYPE_PUBLIC_COMMENT,
                ($addToQueue ? Review::APPROVAL_STATUS_ACCEPTED : Review::APPROVAL_STATUS_WAITING)
            );

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

        //Allow overriding only by Sr AM by Juniors, deny in any other case
        $isReplied = $review->isRepliedPrivately();

        if($isReplied) {
            $accountManager = $this->loadModel($isReplied, 'AccountManager');
            $author  = Yii::app()->getUser()->getUser();

            if(!$author->isSenior() || $accountManager->isSenior()) {
                $message = sprintf(
                    'Private reply NOT posted, already replied to by %s % s (%s Manager)',
                    $accountManager->firstName,
                    $accountManager->lastName,
                    $accountManager->getTypeLabel(false)
                );

                Yii::app()->getUser()->setFlash('error', $message);
                return;
            }
            else {
                //in case of overriding remove previous message
                $privateMessages = $review->privateMessages;
                end($privateMessages)->delete();
            }
        }

        $model = new MessageAnswer;
        $model->setAttributes(array(
            'reviewId'          => $review->id,
            'from'              => Yii::app()->getUser()->getUser()->getFullName(),
            'accountManagerId'  => Yii::app()->getUser()->getUser()->id,
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

            //Add activity record
            $activityModel = new AmActivity;
            $activitySaved = $activityModel->addActivity(
                $model->accountManagerId,
                $review->businessId,
                $review->id,
                'privateMessage',
                $addToQueue ? Message::APPROVAL_STATUS_ACCEPTED : Message::APPROVAL_STATUS_WAITING,
                null,
                $model->id
            );

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
     * Convert Private Message to public Comment
     */
    protected function convertPrivateMessageToPublic($saveAnswer = false)
    {
        $review    = $this->model;
        $messageId = $_POST['Review']['messageId'];

        $model = $this->loadModel($messageId, 'Message');
        $author = $this->loadModel($model->accountManagerId, 'AccountManager');

        $succesMessage = 'A private message to %s has been converted to a public comment, and posted';
        $addToQueue    = true;

        $messageContent = $saveAnswer ? $_POST['Review']['answer'] : $model->messageContent;

        //Force Approval status to "Changed" if the reply content has been changed
        $approvalStatus = $saveAnswer ? MessageAnswer::APPROVAL_STATUS_CHANGED : null;

        if($this->model->answerWithPublicComment($messageContent, $author, $addToQueue, $approvalStatus)) {

            //Update Activity Approval status
            $model->approvalStatus = $saveAnswer? Message::APPROVAL_STATUS_CHANGED: Message::APPROVAL_STATUS_ACCEPTED;
            $model->activityComment = 'Reply Type Changed';
            $model->save();

            $model->delete();
            Yii::app()->getUser()->setFlash('success', sprintf($succesMessage, $review->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A public message has not been posted', $review->userName));
        }
    }

    /**
     * Convert Public Comment to private Message
     */
    protected function convertPublicMessageToPrivate($saveAnswer = false)
    {
        $review = $this->model;

        //Allow overriding only by Sr AM by Juniors, deny in any other case
        $isReplied = $review->isRepliedPrivately();

        if($isReplied) {
            $accountManager = $this->loadModel($isReplied, 'AccountManager');
            $author  = Yii::app()->getUser()->getUser();

            if(!$author->isSenior() || $accountManager->isSenior()) {
                $message = sprintf(
                    'Private reply NOT posted, already replied to by %s % s (%s Manager)',
                    $accountManager->firstName,
                    $accountManager->lastName,
                    $accountManager->getTypeLabel(false)
                );

                Yii::app()->getUser()->setFlash('error', $message);
                return;
            }
            else {
                //in case of overriding remove previous message
                $privateMessages = $review->privateMessages;
                end($privateMessages)->delete();
            }
        }

        //To handle to case the accountManagerId attribute is empty
        $originalAuthor = AccountManager::model()->findByPk($review->accountManagerId);

        if(!$originalAuthor) {
            $originalAuthor = Yii::app()->getUser()->getUser();
        }

        $model = new MessageAnswer;
        $model->setAttributes(array(
            'reviewId'          => $review->id,
            'from'              => $originalAuthor->getFullName(),
            'accountManagerId'  => $originalAuthor->id,
            'userId'            => $review->userId,
            'messageContent'    => $saveAnswer ? $_POST['Review']['answer'] : $review->publicCommentContent,
        ));

        //Force Approval status to "Changed" if the reply content has been changed
        $approvalStatus = $saveAnswer ? MessageAnswer::APPROVAL_STATUS_CHANGED : null;

        $succesMessage = 'A public comment to %s has been converted to a private message, and posted';
        $addToQueue    = true;

        if($model->save(true, null, $addToQueue, $approvalStatus)) {

            $review->clearPublicComment($approvalStatus);
            Yii::app()->getUser()->setFlash('success', sprintf($succesMessage, $review->userName));
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
        if(!isset($_POST['Review']['flagReason']) || empty($_POST['Review']['flagReason']) || empty($_POST['Review']['flagCategory'])) {
            throw new CHttpException(400, 'Invalid input');
        }

        $review = $this->model;

        if($review->isFlagged()) {

            if($review->flagAccountManagerId) {
                $accountManager = $this->loadModel($review->flagAccountManagerId, 'AccountManager');

                $message = sprintf(
                    'Already flagged by %s % s (%s Manager)',
                    $accountManager->firstName,
                    $accountManager->lastName,
                    $accountManager->getTypeLabel(false)
                );
            }
            else {
                $message = 'Already flagged';
            }

            Yii::app()->getUser()->setFlash('error', $message);
            return;
        }

        $authorId  = Yii::app()->getUser()->getUser()->id;
        $succesMessage = 'Review has been flagged';
        $addToQueue    = true;

        //If the user is Jr, change the message and ommit queue adding action
        if(!Yii::app()->getUser()->isSenior()) {
            $succesMessage = 'Your action will be reviewed by your Senior Account Manager before it is posted.';
            $addToQueue    = false;
        }

        if($review->markAsFlagged($_POST['Review']['flagReason'], $_POST['Review']['flagCategory'], $addToQueue, false, $authorId)) {
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
        if($this->model->approvePublicMessage($changed)) {
            Yii::app()->getUser()->setFlash('success', sprintf('A public message has been approved', $this->model->userName));
        }
        else {
            Yii::app()->getUser()->setFlash('error', sprintf('A public message has not been approved', $this->model->userName));
        }
    }

    /**
     * Approve private message
     * @param  boolean $changed set to true if you want to update reply content while approving
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
     * @param  boolean $changed set to true if you want to update reply content while approving
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
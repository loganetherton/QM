<?php
/**
 * This is the model class for table "reviews".
 *
 * The followings are the available columns in table 'reviews':
 * @property integer $id
 * @property integer $businessId
 * @property integer $yelpBusinessId
 * @property string $reviewId
 * @property string $reviewContent
 * @property integer $starRating
 * @property string $reviewDate
 * @property string $userId
 * @property string $userName
 * @property integer $userElite
 * @property string $userLocation
 * @property string $userPhotoLink
 * @property integer $userFriendCount
 * @property integer $userReviewCount
 * @property string $publicCommentContent
 * @property string $publicCommentAuthor
 * @property string $publicCommentDate
 * @property string $flagReason
 * @property string $flaggedAt
 * @property integer $processed
 * @property integer $status
 * @property integer $deleted
 * @property integer $messagesFolder
 * @property integer $replyBlocked
 * @property integer $filtered
 * @property string $latestMessageDate
 * @property string $lastActionAt
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property User $user
 * @property YelpBusiness $yelpBusiness
 * @property ReviewUpdate[] $updates
 * @property Message[] $privateMessages
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Review extends ActiveRecord
{
    const STATUS_OPENED     = 0;
    const STATUS_FOLLOWUP   = 1;
    const STATUS_ARCHIVED   = 2;
    const STATUS_FLAGGED    = 3;
    const STATUS_REPLIED    = 4;

    const PROCESSED_FALSE   = 0;
    const PROCESSED_TRUE    = 1;

    const FILTERED_FALSE    = 0;
    const FILTERED_TRUE     = 1;

    const PM_FOLDER_INBOX       = 0;
    const PM_FOLDER_SENT        = 1;
    const PM_FOLDER_ARCHIVED    = 2;

    const DELETED_TRUE  = 1;
    const DELETED_FALSE = 0;

    /**
     * @var string $currentLatestMessageDate Current value of latestMessageDate attribute
     */
    protected $currentLatestMessageDate;

    /**
     * @var string $currentLastActionAt Current value of lastActionAt attribute
     */
    protected $currentLastActionAt;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className Active record class name.
     * @return Review the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string The associated database table name
     */
    public function tableName()
    {
        return 'reviews';
    }

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('reviewId, reviewContent, starRating, reviewDate', 'required'),
            array('userId, userName, userPhotoLink, userFriendCount, userReviewCount', 'required'),
            array('businessId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('yelpBusinessId', 'exist', 'className'=>'YelpBusiness', 'attributeName'=>'id'),
            array('starRating, userFriendCount, userReviewCount', 'numerical', 'integerOnly'=>true),
            array('userElite, processed, filtered, replyBlocked, deleted', 'boolean'),
            array('userLocation, publicCommentContent, publicCommentAuthor, publicCommentDate', 'safe'),
            array('flagReason, flaggedAt, latestMessageDate, lastActionAt', 'safe'),
            array('status', 'in', 'range'=>array_keys($this->getStatuses())),
            array('messagesFolder', 'in', 'range'=>array_keys($this->getMessagesFolders())),
            array('publicCommentContent, publicCommentAuthor, flagReason', 'filter', 'filter'=>'trim'),
        );
    }

    /**
     * @return array Relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'businessId'),
            'yelpBusiness' => array(self::BELONGS_TO, 'YelpBusiness', 'yelpBusinessId'),

            'updates' => array(self::HAS_MANY, 'ReviewUpdate', 'reviewId', 'index'=>'updateHash'),

            'privateMessages' => array(self::HAS_MANY, 'Message', 'reviewId', 'index'=>'messageHash'),
            'privateMessagesSearch' => array(self::HAS_MANY, 'Message', 'reviewId'),

            'privateMessagesCount' => array(self::STAT, 'Message', 'reviewId'),
        );
    }

    /**
     * @return array Customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'businessId' => 'Business',
            'yelpBusinessId' => 'Yelp Business',
            'reviewId' => 'Review',
            'reviewContent' => 'Review Content',
            'starRating' => 'Star Rating',
            'reviewDate' => 'Review Date',
            'userId' => 'User',
            'userName' => 'User Name',
            'userElite' => 'Elite User',
            'userLocation' => 'User Location',
            'userPhotoLink' => 'User Photo Link',
            'userFriendCount' => 'User Friend Count',
            'userReviewCount' => 'User Review Count',
            'publicCommentContent' => 'Public Comment',
            'publicCommentAuthor' => 'Public Comment Author',
            'publicCommentDate' => 'Public Comment Date',
            'flagReason' => 'Flag Reason',
            'flaggedAt' => 'Flagged At',
            'processed' => 'Processed',
            'status' => 'Status',
            'deleted' => 'Deleted',
            'messagesFolder' => 'Private Message Folder',
            'replyBlocked' => 'Reply Blocked',
            'latestMessageDate' => 'Latest Message Date',
            'lastActionAt' => 'Last Action At',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        parent::afterFind();

        // Save current lastActionAt and latestMessageDate attributes values.
        // This prevents before overridding lastActionAt and latestMessageDate values with wrong value
        // (see beforeSave method)
        $this->currentLatestMessageDate = $this->latestMessageDate;
        $this->currentLastActionAt      = $this->lastActionAt;
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        // Make sure latestMessageDate attribute is not changed to lower value
        if(strtotime($this->currentLatestMessageDate) > strtotime($this->latestMessageDate)) {
            $this->latestMessageDate = $this->currentLatestMessageDate;
        }

        // Make sure lastActionAt attribute is not changed to lower value
        if(strtotime($this->currentLastActionAt) > strtotime($this->lastActionAt)) {
            $this->lastActionAt = $this->currentLastActionAt;
        }

        return parent::beforeSave();
    }

    /**
     * This method is invoked after saving a record successfully.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave()
    {
        parent::afterSave();

        if(Yii::app()->hasComponent('user') && $this->user->hasAccountManager()) {
            Yii::app()->getUser()->setReviewsCount($this->user->accountManager->getReviewsCount());
            Yii::app()->getUser()->setMessagesCount($this->user->accountManager->getMessagesCount());
        }

        // Increment cached number of reviews
        if($this->getIsNewRecord()) {
            $this->yelpBusiness->incrementReviewsCount();
        }
    }

    /**
     * Returns the default named scope that should be implicitly applied to all queries for this model.
     * Note, default scope only applies to SELECT queries. It is ignored for INSERT, UPDATE and DELETE queries.
     * @return array the query criteria. This will be used as the parameter to the constructor
     * of {@link CDbCriteria}.
     */
    public function defaultScope()
    {
        return array(
            'condition'=>sprintf('%s.deleted = :deleted', $this->getTableAlias(false, false)),
            'params'=>array(':deleted'=>self::DELETED_FALSE),
        );
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'opened'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_OPENED),
            ),
            'followup'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_FOLLOWUP),
            ),
            'archived'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_ARCHIVED),
            ),
            'flagged'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_FLAGGED),
            ),
            'replied'=>array(
                'condition'=>'t.status = :status',
                'params'=>array(':status'=>self::STATUS_REPLIED),
            ),
            'processed'=>array(
                'condition'=>'t.processed = :processed',
                'params'=>array(':processed'=>self::PROCESSED_TRUE),
            ),
            'notProcessed'=>array(
                'condition'=>'t.processed = :processed',
                'params'=>array(':processed'=>self::PROCESSED_FALSE),
            ),
            'filtered'=>array(
                'condition'=>'t.filtered = :filtered',
                'params'=>array(':filtered'=>self::FILTERED_TRUE),
            ),
            'notFiltered'=>array(
                'condition'=>'t.filtered = :filtered',
                'params'=>array(':filtered'=>self::FILTERED_FALSE),
            ),

            // Private messages folders
            'pmInbox'=>array(
                'condition'=>'t.messagesFolder = :folder',
                'params'=>array(':folder'=>self::PM_FOLDER_INBOX),
            ),
            'pmNotProcessed'=>array(
                'condition'=>'t.processed = :processed',
                'params'=>array(':processed'=>self::PROCESSED_FALSE),
            ),
            'pmSent'=>array(
                'condition'=>'t.messagesFolder = :folder',
                'params'=>array(':folder'=>self::PM_FOLDER_SENT),
            ),
            'pmArchived'=>array(
                'condition'=>'t.messagesFolder = :folder',
                'params'=>array(':folder'=>self::PM_FOLDER_ARCHIVED),
            ),
        );
    }

    /**
     * Business scope.
     * @param mixed $business Business id or an array of ids
     * @return object self
     */
    public function businessScope($business)
    {
        $criteria = new CDbCriteria;

        if(is_array($business)) {
            $criteria->addInCondition('t.businessId', $business);
        }
        else {
            $criteria->compare('t.businessId', $business);
        }

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Yelp business scope.
     * @param mixed $yelpBusiness Yelp business id or an array of ids
     * @return object self
     */
    public function yelpBusinessScope($yelpBusiness)
    {
        $criteria = new CDbCriteria;

        if(is_array($yelpBusiness)) {
            $criteria->addInCondition('t.yelpBusinessId', $yelpBusiness);
        }
        else {
            $criteria->compare('t.yelpBusinessId', $yelpBusiness);
        }

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider The data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Find model by review id.
     * @param string $reviewId Review id
     * @return mixed The record found. Null if none is found.
     */
    public function findByReviewId($reviewId)
    {
        return $this->findByAttributes(array('reviewId'=>$reviewId));
    }

    /**
     * @return boolean Whether user is marked as elite
     */
    public function getIsEliteUser()
    {
        return $this->userElite;
    }

    /**
     * @return boolean Whether this review is filtered or not
     */
    public function getIsFiltered()
    {
        return (bool) $this->filtered;
    }

    /**
     * Returns Lates Private Message Date.
     * This value is being used in Private Messages.
     * @return string Latest Private Message Date
     */
    public function getLatestMessageDate()
    {
        if(!$this->hasPrivateMessages()) {
            return 'No private messages';
        }
        else {
            return Yii::app()->getComponent('format')->formatDate($this->latestMessageDate);
        }
    }

    /**
     * Returns "Last taken action" attribute.
     * "No action yet" label is displayed for opened reviews with no public comment or private message.
     * Otherwise it returns formatted lastActionAt attribute.
     * @return string Last taken action
     */
    public function getLastActionTime()
    {
        if($this->isOpened() && !$this->hasPrivateMessages() && !$this->hasPublicComment()) {
            return $this->isProcessed() ? 'Read' : 'No action yet';
        }
        else {
            return Yii::app()->getComponent('format')->formatDate($this->lastActionAt);
        }
    }

    /**
     * @return array List of available statuses
     */
    public function getStatuses()
    {
        return array(
            self::STATUS_OPENED     => 'Opened',
            self::STATUS_FOLLOWUP   => 'Follow Up',
            self::STATUS_ARCHIVED   => 'Archived',
            self::STATUS_FLAGGED    => 'Flagged',
            self::STATUS_REPLIED    => 'Replied',
        );
    }

    /**
     * @return array List of available private message folders
     */
    public function getMessagesFolders()
    {
        return array(
            self::PM_FOLDER_INBOX       => 'Inbox',
            self::PM_FOLDER_SENT        => 'Sent',
            self::PM_FOLDER_ARCHIVED    => 'Archived',
        );
    }

    /**
     * @return boolean Whether review has an update
     */
    public function hasUpdates()
    {
        return (bool)count($this->updates);
    }

    /**
     * @return boolean Whether review has a public comment
     */
    public function hasPublicComment()
    {
        return trim($this->publicCommentContent) != '';
    }

    /**
     * @return boolean Whether review has a private comment
     */
    public function hasPrivateMessages()
    {
        return (bool)count($this->privateMessages);
    }

    /**
     * @return boolean Whether private comment is available
     */
    public function isPrivateCommentAllowed()
    {
        $privateMessages = $this->privateMessages;

        if(count($privateMessages) == 0) {
            return true;
        }

        $lastMessage = end($privateMessages);

        return $lastMessage->from == $this->userName;
    }

    /**
     * @return boolean Whether review is opened
     */
    public function isOpened()
    {
        return $this->status == self::STATUS_OPENED;
    }

    /**
     * Mark review as opened.
     * Only reviews from replied stack can be moved back to opened.
     * @param boolean $save Whether save the model or just change the status
     * @return boolean|void Whether the saving suceeded (or void if save is set to false)
     */
    public function moveToOpened($save = false)
    {
        if($this->isReplied()) {
            $this->status = self::STATUS_OPENED;
        }

        // There's a little trick in here ;)
        $this->processed = self::PROCESSED_FALSE;

        if($save) {
            return $this->save();
        }
    }

    /**
     * @return boolean Whether reviews is marked to be follow up
     */
    public function isFollowUp()
    {
        return $this->status == self::STATUS_FOLLOWUP;
    }

    /**
     * Move review to Follow Up stack.
     * @return boolean Whether review has been successfully moved to Follow Up stack
     */
    public function moveToFollowUp()
    {
        $this->status = self::STATUS_FOLLOWUP;

        return $this->save();
    }

    /**
     * @return boolean Whether review is archived
     */
    public function isArchived()
    {
        return $this->status == self::STATUS_ARCHIVED;
    }

    /**
     * Mark review as archived.
     * @return boolean Whether the saving suceeded
     */
    public function markAsArchived()
    {
        $this->status = self::STATUS_ARCHIVED;

        return $this->save();
    }

    /**
     * @return boolean Whether review is flagged
     */
    public function isFlagged()
    {
        return $this->status == self::STATUS_FLAGGED;
    }

    /**
     * Mark review as flagged.
     * @param string $reason Flag reason
     * @return boolean Whether flagging suceeded
     */
    public function markAsFlagged($reason = '', $reasonCategory = null)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            $this->status       = self::STATUS_FLAGGED;
            $this->flagReason   = trim($reason);
            $this->flaggedAt    = date('Y-m-d H:i:s');
            $this->lastActionAt = date('Y-m-d H:i:s');

            if(!$this->save()) {
                throw new CDbException("Review {$this->id} has not been flagged");
            }

            // Add flag action to phantom queue
            $queueModel = new Queue;
            if(!$queueModel->addToQueue('flagReview', array($this->id, $reason, $reasonCategory))) {
                throw new CDbException("Review {$this->id} failed while adding to flag queue");
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }

    /**
     * @return boolean Whether review has a reply
     */
    public function isReplied()
    {
        return $this->status == self::STATUS_REPLIED;
    }

    /**
     * Mark review as replied.
     * Only opened (new) review can be moved to replied stack.
     * @param boolean $save Whether save the model or just change the status
     * @return boolean|void Whether the saving suceeded (or void if save is set to false)
     */
    public function markAsReplied($save = false)
    {
        if($this->isOpened()) {
            $this->status = self::STATUS_REPLIED;
            $this->processed = self::PROCESSED_TRUE;

            if($save) {
                return $this->save();
            }
        }
    }

    /**
     * @return boolean Whether review is processed
     */
    public function isProcessed()
    {
        return $this->processed == self::PROCESSED_TRUE;
    }

    /**
     * Mark review as unprocessed.
     * @param boolean $save Whether save the model or just change the status
     * @return boolean|void Whether the saving suceeded (or void if save is set to false)
     */
    public function markAsUnprocessed($save = false)
    {
        $this->processed = self::PROCESSED_FALSE;

        if($save) {
            return $this->save();
        }
    }

    /**
     * Mark review as processed.
     * @return boolean Whether the saving suceeded
     */
    public function markAsProcessed()
    {
        $this->processed = self::PROCESSED_TRUE;

        return $this->save();
    }

    /**
     * Mark review as deleted.
     * @return boolean Whether the save succeeds
     */
    public function markAsDeleted()
    {
        $this->deleted = self::DELETED_TRUE;

        return $this->save();
    }

    /**
     * Reply to message with public comment.
     * @param string $comment Public comment
     * @param string $author Comment author
     * @return boolean Whether the saving suceeded
     */
    public function answerWithPublicComment($comment, $author)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            $this->setAttributes(array(
                'publicCommentContent'  => $comment,
                'publicCommentAuthor'   => $author,
                'publicCommentDate'     => date('Y-m-d H:i:s'),
                'lastActionAt'          => date('Y-m-d H:i:s'),
            ));

            $this->markAsReplied();

            if(!$this->save()) {
                throw new CDbException("Public comment to review {$this->id} has not been made");
            }

            // Add flag action to phantom queue
            $queueModel = new Queue;
            if(!$queueModel->addToQueue('publicComment', array($this->id))) {
                throw new CDbException("Public comment to review {$this->id} has not been added to queue");
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }

    /**
     * Move private messages thread back to inbox folder.
     * @param boolean $save Whether save the model or just change the status
     * @return boolean|void Whether the saving suceeded (or void if save is set to false)
     */
    public function moveMessagesThreadToInbox($save = false)
    {
        $this->messagesFolder = self::PM_FOLDER_INBOX;

        if($save) {
            return $this->save();
        }
    }

    /**
     * Move private messages thread to sent folder.
     * @param boolean $save Whether save the model or just change the status
     * @return boolean|void Whether the saving suceeded (or void if save is set to false)
     */
    public function moveMessagesThreadToSent($save = false)
    {
        if(!$this->isPmArchived()) {
            $this->messagesFolder = self::PM_FOLDER_SENT;
        }

        if($save) {
            return $this->save();
        }
    }

    /**
     * @return boolean Whether private messages thread is archived
     */
    public function isPmArchived()
    {
        return $this->messagesFolder == self::PM_FOLDER_ARCHIVED;
    }

    /**
     * Move private messages thread to archived folder.
     * @return boolean Whether the saving suceeded
     */
    public function moveMessagesThreadToArchived()
    {
        $this->messagesFolder = self::PM_FOLDER_ARCHIVED;

        return $this->save();
    }
}
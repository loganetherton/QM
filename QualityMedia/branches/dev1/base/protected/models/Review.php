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
 * @property string $accountManagerId
 * @property string $flagAccountManagerId
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
 * @property string $flagReasonCategory
 * @property string $flaggedAt
 * @property integer $processed
 * @property integer $status
 * @property integer $oldStatus
 * @property integer $approvalStatus
 * @property integer $totalApprovalStatus
 * @property integer $flagApprovalStatus
 * @property integer $deleted
 * @property integer $messagesFolder
 * @property integer $replyBlocked
 * @property integer $filtered
 * @property integer $postingStatus
 * @property string $latestMessageDate
 * @property string $seniorAmNote
 * @property string $seniorAmNoteUpdateDate
 * @property integer $precontract
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
 * Original Approval Statuses
 *
 * @property integer $originalApprovalStatus
 * @property integer $originalFlagApprovalStatus
 *
 * Temporary activity comment
 * @property activityComment
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

    const APPROVAL_STATUS_OPEN = 0;
    const APPROVAL_STATUS_WAITING = 1;
    const APPROVAL_STATUS_ACCEPTED = 2;
    const APPROVAL_STATUS_CHANGED = 3;
    const APPROVAL_STATUS_REJECTED = 4;

    const PRECONTRACT_FALSE    = 0;
    const PRECONTRACT_TRUE     = 1;
    //The constant below is used for the precontract reviews older then considered as PRECONTRACT_TRUE
    const PRECONTRACT_OLDER     = 2;

    /**
     * @var string $currentLatestMessageDate Current value of latestMessageDate attribute
     */
    protected $currentLatestMessageDate;

    /**
     * @var string $currentLastActionAt Current value of lastActionAt attribute
     */
    protected $currentLastActionAt;

    /**
     * @var integer Original statuses handlers
     */
    public $originalApprovalStatus;
    public $originalFlagApprovalStatus;

    /**
     * Activity Comment
     */
    public $activityComment;

    /**
     * @var string $updateTotalApprovalStatus if enabled, the total approval status will be updated on save
     */
    public $updateTotalApprovalStatus = true;

    /**
     * @var integer base precontract limit per client
     */
    public $precontractLimit = 10;

    /**
     * @var integer time period in hours used in checking if a review can be considered as new or not
     */
    public $lastHoursPeriod = 48;

    /**
     * @var integer review base rating count used in filtering the reviews by star rating
     */
    public $starRatingBound = 4;

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
            array('accountManagerId, flagAccountManagerId', 'exist', 'className'=>'AccountManager', 'attributeName'=>'id'),
            array('yelpBusinessId', 'exist', 'className'=>'YelpBusiness', 'attributeName'=>'id'),
            array('starRating, userFriendCount, userReviewCount, flagReasonCategory, precontract, postingStatus', 'numerical', 'integerOnly'=>true),
            array('userElite, processed, filtered, replyBlocked, deleted', 'boolean'),
            array('userLocation, publicCommentContent, publicCommentAuthor, publicCommentDate', 'safe'),
            array('seniorAmNote, flagReason, flaggedAt, latestMessageDate, lastActionAt, seniorAmNoteUpdateDate', 'safe'),
            array('status, oldStatus', 'in', 'range'=>array_keys($this->getStatuses())),
            array('approvalStatus, flagApprovalStatus', 'in', 'range'=>array_keys($this->getApprovalStatuses())),
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
            'accountManagerId' => 'Account Manager Id',
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
            'flagReasonCategory' => 'Flag Reason Category',
            'flaggedAt' => 'Flagged At',
            'processed' => 'Processed',
            'status' => 'Status',
            'approvalStatus' => 'Approval Status',
            'deleted' => 'Deleted',
            'messagesFolder' => 'Private Message Folder',
            'replyBlocked' => 'Reply Blocked',
            'latestMessageDate' => 'Latest Message Date',
            'seniorAmNote' => 'Senior AM Note',
            'seniorAmNoteUpdateDate' => 'Senior AmN ote Update Date',
            'precontract' => 'Pre-contract',
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

        //Save original statuses
        $this->originalApprovalStatus = (int) $this->approvalStatus;
        $this->originalFlagApprovalStatus = (int) $this->flagApprovalStatus;
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

        //Recalculate the total approval Status
        if($this->updateTotalApprovalStatus) {
            $this->totalApprovalStatus = $this->getTotalApprovalStatus();
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

        $isNewRecord = $this->getIsNewRecord();

        // Increment cached number of reviews
        if($isNewRecord) {
            $this->yelpBusiness->incrementReviewsCount();
        }

        //Update related activity record, if exists and the approval statues has been changed
        if(!$isNewRecord) {

            if($this->originalApprovalStatus != $this->approvalStatus) {
                $this->updateRelatedAmActivityStatus(AmActivity::TYPE_PUBLIC_COMMENT);
                $this->activityComment = null;

            }

            if($this->originalFlagApprovalStatus != $this->flagApprovalStatus) {
                $this->updateRelatedAmActivityStatus(AmActivity::TYPE_FLAG);
                $this->activityComment = null;
            }
        }
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
            'precontract'=>array(
                'condition'=>'t.precontract = :precontract',
                'params'=>array(':precontract'=>self::PRECONTRACT_TRUE),
            ),
            'notPrecontract'=>array(
                'condition'=>'t.precontract = :precontract',
                'params'=>array(':precontract'=>self::PRECONTRACT_FALSE),
            ),
            'olderPrecontract'=>array(
                'condition'=>'t.precontract = :precontract',
                'params'=>array(':precontract'=>self::PRECONTRACT_OLDER),
            ),
            'excludeOlderPrecontract'=>array(
                'condition'=>'t.precontract != :precontract',
                'params'=>array(':precontract'=>self::PRECONTRACT_OLDER),
            ),
            'deleted'=>array(
                'condition'=>'t.deleted = :deleted',
                'params'=>array(':deleted'=>self::DELETED_TRUE),
            ),
            'notDeleted'=>array(
                'condition'=>'t.deleted = :deleted',
                'params'=>array(':deleted'=>self::DELETED_FALSE),
            ),
            'active'=>array(
                'condition'=>'user.status = :userStatus',
                'params'=>array(':userStatus'=>Client::STATUS_ENABLED),
            ),
            'inactive'=>array(
                'condition'=>'user.status = :userStatus',
                'params'=>array(':userStatus'=>Client::STATUS_DISABLED),
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
     * Limits search criteria to the reviews considered as waiting in the queue be replied
     * @return object self
     */
    public function inQueueScope()
    {
        //Limit to opened reviews
        $this->opened();

        $criteria = new CDbCriteria;

        //Limit to postcontract, lastest 10 precontract reviews and filtered with 4-5 stars rating
        $criteria->addCondition('
            t.precontract = :precontract
            OR (t.precontract = :postcontract AND t.filtered = :notFiltered)
            OR (t.precontract = :postcontract AND (t.filtered = :filtered AND t.starRating >= :starRating))
        ');

        $criteria->params = array(
            ':postcontract' => self::PRECONTRACT_FALSE,
            ':precontract'  => self::PRECONTRACT_TRUE,
            ':filtered'  => self::FILTERED_TRUE,
            ':notFiltered'  => self::FILTERED_FALSE,
            ':starRating' => $this->starRatingBound,
        );

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Limits search criteria to the filtered reviews with additional conditions added to better usability
     * @return object self
     */
    public function filteredScope()
    {
        //Limit to opened reviews
        $this->opened();

        //Limit to filtered reviews
        $this->filtered();

        $criteria = new CDbCriteria;

        //Limit to older precontract or postcontract with lower star rating reviews
        $criteria->addCondition('t.precontract = :olderPrecontract OR (t.precontract = :postcontract AND t.starRating < :starRating)');

        $criteria->params = array(
            ':postcontract' => self::PRECONTRACT_FALSE,
            ':olderPrecontract'  => self::PRECONTRACT_OLDER,
            ':starRating' => $this->starRatingBound
        );

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Limits the results to last hours period
     * @param int $starRating Minimal allowed star rating
     * @return object self
     */
    public function lastHoursScope($hoursNumber = null)
    {
        if($hoursNumber == null) {
            $hoursNumber = $this->lastHoursPeriod;
        }
        $criteria = new CDbCriteria;

        $minDate = date('Y-m-d H:i:s', strtotime(sprintf('-%s hours', (int) $hoursNumber)));

        $criteria->addCondition("t.createdAt >= '{$minDate}'");

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Excludes the last hours period
     * @param int $starRating Minimal allowed star rating
     * @return object self
     */
    public function excludeLastHoursScope($hoursNumber = null)
    {
        if($hoursNumber == null) {
            $hoursNumber = $this->lastHoursPeriod;
        }
        $criteria = new CDbCriteria;

        $minDate = date('Y-m-d H:i:s', strtotime(sprintf('-%s hours', (int) $hoursNumber)));

        $criteria->addCondition("t.reviewDate < '{$minDate}'");

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Minimal Star Rating scope.
     * @param int $starRating Minimal allowed star rating
     * @return object self
     */
    public function minStarRatingScope($starRating = null)
    {

        if($starRating == null) {
            $starRating = $this->starRatingBound;
        }

        $criteria = new CDbCriteria;

        $criteria->addCondition('t.starRating >='. (int) $starRating);

        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Max Star Rating scope.
     * @param int $starRating Minimal allowed star rating
     * @return object self
     */
    public function maxStarRatingScope($starRating = null)
    {

        if($starRating == null) {
            $starRating = $this->starRatingBound - 1;
        }

        $criteria = new CDbCriteria;

        $criteria->addCondition('t.starRating <='. (int) $starRating);

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
     * Returns Latest Private Message Date.
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
     * Returns Latest received Private Message Date.
     * This value is being used in Private Messages.
     * @return string Latest Received Private Message Date
     */
    public function getLatestReceivedMessageDate()
    {
        if(!$this->hasPrivateMessages()) {
            return 'No private messages';
        }
        else {

            $sortedMessages = $this->privateMessages;

            //Sort messages
            usort($sortedMessages, function($a, $b) {
                if (strtotime($a->messageDate) == strtotime($b->messageDate)) {
                    return 0;
                }

                return strtotime($a->messageDate) > strtotime($b->messageDate) ? 1 : -1;
            });

            $clientMessages = array();
            $date = $this->reviewDate;

            foreach ($sortedMessages as $message) {
                if(!$message->isStaffAnswer() && !$message->isBizOwnerAnswer()) {
                    array_push($clientMessages, $message);
                }
            }

            if(count($clientMessages)) {
               $date = end($clientMessages)->messageDate;
            }

            if($this->hasUpdates()) {
                $updates = array_values($this->updates);
                $lastUpdate = end($updates)->updateDate;

                if(strtotime($lastUpdate) > strtotime($date)) {
                    $date = $lastUpdate;
                }
            }

            return Yii::app()->getComponent('format')->formatDate($date);
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
     * @return array List of available Senior AM approval statuses
     */
    public function getApprovalStatuses()
    {
        return array(
            self::APPROVAL_STATUS_OPEN  => 'Open',
            self::APPROVAL_STATUS_WAITING  => 'Awaiting',
            self::APPROVAL_STATUS_ACCEPTED => 'Approved',
            self::APPROVAL_STATUS_CHANGED  => 'Changed',
            self::APPROVAL_STATUS_REJECTED => 'Rejected',
        );
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
     * @return boolean Whether review response is waiting for approval
     */
    public function isWaitingForApproval()
    {
        return $this->approvalStatus == self::APPROVAL_STATUS_WAITING;
    }

    /**
     * @return boolean Whether review response is waiting for a flag approval
     */
    public function isWaitingForFlagApproval()
    {
        return $this->flagApprovalStatus == self::APPROVAL_STATUS_WAITING;
    }

    /**
     * Returns Total Approval Status for the review and related private messages
     * @return int Status Flag
     */
    public function getTotalApprovalStatus()
    {
        // "WAITING" status is always the top priority.
        if($this->isWaitingForApproval()) {
            return self::APPROVAL_STATUS_WAITING;
        }

        $status = $this->approvalStatus;
        $lastUpdate = strtotime($this->lastActionAt);

        //Check the latest message(if any exists), compare update time with the public comment, then use the newst one
        if($this->hasPrivateMessages()) {
            $sortedMessages = $this->privateMessages;

            //Sort messages from last to first, prioritizing "waiting" approval status
            usort($sortedMessages, function($a, $b) {
                if (strtotime($a->updatedAt) == strtotime($b->updatedAt)) {
                    return 0;
                }

                return ($a->isWaitingForApproval() || strtotime($a->updatedAt) > strtotime($b->updatedAt)) ? 1 : -1;
            });

            //filter to only sent messages
            $sortedSentMessages = array();

            foreach ($sortedMessages as $message) {
                if($message->isStaffAnswer() || $message->isBizOwnerAnswer()) {
                    array_push($sortedSentMessages, $message);
                }
            }

            if(count($sortedSentMessages)) {

                $lastMessage = end($sortedSentMessages);

                if($lastMessage->isWaitingForApproval() || strtotime($lastMessage->updatedAt) >= $lastUpdate) {
                    $status = $lastMessage->approvalStatus;
                }
            }

        }

        return $status;
    }

    /**
     * Returns Status for private messages section results
     * @return int Status Flag
     */
    public function getPmApprovalStatus()
    {
        // "WAITING" status is always the top priority.
        if($this->isWaitingForApproval()) {
            return self::APPROVAL_STATUS_WAITING;
        }

        $status = $this->approvalStatus;

        //Check the latest message(if any exists), compare update time with the public comment, then use the newst one
        if($this->isPrivateCommentAllowed()) {
            return self::APPROVAL_STATUS_OPEN;
        }
        else {

            $privateMessages = $this->privateMessages;

            if(count($privateMessages)) {
                $lastMessage = end($privateMessages);
            }

            $status =  $lastMessage->approvalStatus;
        }

        return $status;
    }

    /**
     * Returns Flag Approval Status
     * @return [type] [description]
     */
    public function getFlagApprovalStatus()
    {
        return 1;
    }


    /**
     * Whether this AM's flag was rejected
     *
     * @access public
     * @return bool
     */
    public function isFlagRejected()
    {
        return $this->flagApprovalStatus == self::APPROVAL_STATUS_REJECTED;
    }

    /**
     * Mark review as flagged.
     * @param string $reason Flag reason
     * @param boolean $approved Informs if the message was approved by Senior AM
     * @param boolean $changed Informs if the message was changed by Senior AM
     * @param boolean $rejected
     * @return boolean Whether flagging suceeded
     */
    public function markAsFlagged($reason = '', $reasonCategory = null, $approved = false, $changed = false, $authorId = null, $rejected = false)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {

            $flagApprovalStatus = self::APPROVAL_STATUS_WAITING;

            if($approved) {
                $flagApprovalStatus = $changed ? self::APPROVAL_STATUS_CHANGED : self::APPROVAL_STATUS_ACCEPTED;
            }

            if ($rejected) {
                $flagApprovalStatus = self::APPROVAL_STATUS_REJECTED;
            }
            else {
                $this->oldStatus = $this->status;
            }

            $lastActionDate = date('Y-m-d H:i:s');

            $this->status         = $rejected ? $this->oldStatus : self::STATUS_FLAGGED;
            $this->flagReason     = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $reason));
            $this->flagReasonCategory = (int) $reasonCategory;
            $this->flaggedAt      = $lastActionDate;
            $this->lastActionAt   = $rejected ? '0000-00-00 00:00:00' : $lastActionDate;
            $this->flagApprovalStatus = $flagApprovalStatus;

            if($authorId) {
                $this->flagAccountManagerId = $authorId;

            //Add activity record
            $activityModel = new AmActivity;
            $activitySaved = $activityModel->addActivity($authorId, $this->businessId, $this->id, 'flag', $flagApprovalStatus);
            }


            if(!$this->save()) {
                throw new CDbException("Review {$this->id} has not been flagged");
            }

            // If the answer is approved, add it to the queue
            if($approved && !$rejected) {
                $queueModel = new Queue;
                if(!$queueModel->addToQueue('flagReview', array($this->id, $reason, $reasonCategory))) {
                    throw new CDbException("Review {$this->id} failed while adding to flag queue");
                }
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
     * Mark review as awaiting for the Senior Am Approval.
     * @return boolean Whether the save succeeds
     */
    public function markAsWaitingForApproval()
    {
        $this->approvalStatus = self::APPROVAL_STATUS_WAITING;

        return $this->save();
    }


    /**
     * Reply to message with public comment.
     * @param string $comment Public comment
     * @param string $author Comment author
     * @param boolean $approved Informs if the message was approved by Senior AM
     * @param boolean $approvalStatus force custom ApprovalStatus
     * @return boolean Whether the saving suceeded
     */
    public function answerWithPublicComment($comment, $author, $approved = false, $approvalStatus = null)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        if($approvalStatus == null) {
            $approvalStatus = $approved ? self::APPROVAL_STATUS_ACCEPTED : self::APPROVAL_STATUS_WAITING;
        }

        try {
            $this->setAttributes(array(
                'publicCommentContent'  => $comment,
                'publicCommentAuthor'   => $author->getFullName(),
                'publicCommentDate'     => date('Y-m-d H:i:s'),
                'lastActionAt'          => date('Y-m-d H:i:s'),
                'accountManagerId'      => $author->id,
                'approvalStatus'        => $approvalStatus
            ));

            $this->markAsReplied();

            if(!$this->save()) {
                throw new CDbException("Public comment to review {$this->id} has not been made");
            }

            // If the answer is approved, add it to the queue
            if($approved) {
                // Add public comment action to phantom queue
                $queueModel = new Queue;
                if(!$queueModel->addToQueue('publicComment', array($this->id))) {
                    throw new CDbException("Public comment to review {$this->id} has not been added to queue");
                }
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
     * Clears the public content (used in changing the reply type from public to private)
     */
    public function clearPublicComment($approvalStatus = null)
    {
        if($approvalStatus == null) {
            $approvalStatus = self::APPROVAL_STATUS_ACCEPTED;
        }

        $this->setAttributes(array(
            'publicCommentContent'  => null,
            'publicCommentAuthor'   => null,
            'publicCommentDate'     => '0000-00-00 00:00:00',
            //Update related activity status
            'approvalStatus'        => $approvalStatus,
        ));

        $this->activityComment = 'Reply Type Changed';

        $this->save();
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

    /**
     * Approve awaiting public response
     * @param  boolean $changed information if the Senior AM changed the message by himself
     * @return boolean Whether the saving suceeded
     */
    public function approvePublicMessage($changed = false)
    {
        // Add public comment action to phantom queue
        $queueModel = new Queue;

        if(!$queueModel->addToQueue('publicComment', array($this->id))) {
            throw new CDbException("Public comment to review {$this->id} has not been added to queue");
        }

        $this->approvalStatus = $changed ? self::APPROVAL_STATUS_CHANGED : self::APPROVAL_STATUS_ACCEPTED;

        return $this->save();
    }

    /**
     * Approve awaiting flag
     * @param  boolean $changed information if the Senior AM changed the message by himself
     * @return boolean Whether the saving suceeded
     */
    public function approveFlag($changed = false)
    {
        // Add public comment action to phantom queue
        $queueModel = new Queue;

        if(!$queueModel->addToQueue('flagReview', array($this->id, $this->flagReason, $this->flagReasonCategory))) {
            throw new CDbException("Review {$this->id} failed while adding to flag queue");
        }

        $this->flagApprovalStatus = $changed ? self::APPROVAL_STATUS_CHANGED : self::APPROVAL_STATUS_ACCEPTED;

        return $this->save();
    }

    public function isRepliedPrivately()
    {
        $privateMessages = $this->privateMessages;

        if(count($privateMessages) == 0) {
            return null;
        }

        $lastMessage = end($privateMessages);

        if($lastMessage->from == $this->userName) {
            return null;
        } else {
            return $lastMessage->accountManagerId;
        }
    }

    public function isRepliedPublicly()
    {
        return $this->accountManagerId;
    }

    /**
     * Returns whether this review is pre-contract or not
     *
     * @access publicprecontract
     * @return bool
     */
    public function isPrecontract()
    {
        return $this->precontract == self::PRECONTRACT_TRUE;
    }

    /**
     * Updated the status of the related activity record, if exists
     */
    public function updateRelatedAmActivityStatus($type, $status = null)
    {
        $criteria = new CDbCriteria;

        $criteria->compare('reviewId', $this->id);
        // $criteria->compare('accountManagerId', $this->accountManagerId);
        $criteria->compare('type', $type);

        $activity = AmActivity::model()->find($criteria);

        if($activity) {

            if(!$status) {
                $status = ($type == AmActivity::TYPE_FLAG ? $this->flagApprovalStatus : $this->approvalStatus);
            }

            //If the activity comment exits, add it
            if(!empty($this->activityComment)) {
                $activity->comment = $this->activityComment;
            }

            $activity->status = $status;
            $activity->save();
        }
    }

    /**
     * Update total Approval status(Based on the approval statuses from last message and the review itself)
     * @return boolean Whether the saving suceeded
     */
    public function updateTotalApprovalStatus()
    {
        $this->totalApprovalStatus = $this->getTotalApprovalStatus();

        //disable automatic updating
        $this->updateTotalApprovalStatus = false;

        return $this->save();
    }

    /**
     * Updates precontract reviews
     * @param  int $clientId client Id
     * @param  string $contractDate client contract Date
     * @return boolean Whether the update suceeded
     */
    public function recalculatePrecontractReviews($clientId, $contractDate)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        //Reset precontract valeues for the client reviews
        $criteria = new CDbCriteria();
        $criteria->addCondition("businessId = {$clientId}");

        $resetResult = $this->updateAll(array('precontract' => 0), $criteria);

        //Update set new precontract reviews
        $criteria->addCondition("reviewDate < '{$contractDate}'");
        $criteria->order = 'reviewDate DESC, id DESC';

        $updateResult = $this->updateAll(array('precontract' => self::PRECONTRACT_OLDER), $criteria);

        $criteria->addCondition("deleted = ".self::DELETED_FALSE);
        $criteria->limit = $this->precontractLimit;

        $updateResult = $this->updateAll(array('precontract' => self::PRECONTRACT_TRUE), $criteria);

        $transaction->commit();

        return $this;
    }
}
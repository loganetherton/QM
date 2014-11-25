<?php
/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property integer $id
 * @property integer $reviewId
 * @property string $userId
 * @property string $accountManagerId
 * @property string $messageThread
 * @property string $messageType
 * @property string $from
 * @property string $messageContent
 * @property string $messageHash
 * @property string $messageDate
 * @property integer $processed
 * @property integer $source
 * @property integer $sent
 * @property integer $approvalStatus
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property Review $review
 *
 * Original Approval Statuses
 *
 * @property integer $originalApprovalStatus
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Message extends ActiveRecord
{
    const PROCESSED_FALSE = 0;
    const PROCESSED_TRUE = 1;

    const SOURCE_DASHBOARD = 1;
    const SOURCE_YELP = 2;

    const APPROVAL_STATUS_OPEN = 0;
    const APPROVAL_STATUS_WAITING = 1;
    const APPROVAL_STATUS_ACCEPTED = 2;
    const APPROVAL_STATUS_CHANGED = 3;

    /**
     * @var integer Original statuses handlers
     */
    public $originalApprovalStatus;

    /**
     * Activity Comment
     */
    public $activityComment;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className Active record class name.
     * @return Message The static model class
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
        return 'messages';
    }

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('reviewId, userId, messageType, from, messageContent, messageDate', 'required'),
            array('reviewId', 'exist', 'className'=>'Review', 'attributeName'=>'id'),
            array('accountManagerId', 'exist', 'className'=>'AccountManager', 'attributeName'=>'id'),
            array('processed, sent', 'boolean'),
            array('approvalStatus', 'in', 'range'=>array_keys($this->getApprovalStatuses())),
            array('source', 'in', 'range'=>array_keys($this->getSources())),
            array('messageThread, messageHash', 'safe'),
        );
    }

    /**
     * @return array Relational rules.
     */
    public function relations()
    {
        return array(
            'review' => array(self::BELONGS_TO, 'Review', 'reviewId'),
        );
    }

    /**
     * @return array Customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'reviewId' => 'Review',
            'messageThread' => 'Thread',
            'userId' => 'User',
            'accountManagerId' => 'Account Manager Id',
            'messageType' => 'Type',
            'from' => 'From',
            'messageContent' => 'Message Content',
            'messageHash' => 'Message Hash',
            'messageDate' => 'Message Date',
            'processed' => 'Processed',
            'source' => 'Source',
            'sent' => 'Sent',
            'approvalStatus' => 'Approval Status',
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

        //Save original statuses
        $this->originalApprovalStatus = (int) $this->approvalStatus;
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        // Remove invalid line breaks and possible encoding
        $this->messageContent = str_replace("\r\n", "\n", trim($this->messageContent));
        $this->messageContent = iconv('UTF-8', 'ASCII//TRANSLIT', $this->messageContent);

        // Build message hash
        $this->messageHash = sha1($this->userId.$this->messageContent);

        return parent::beforeSave();
    }

    /**
     * This method is invoked after saving a record successfully.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave()
    {
        parent::afterSave();


        //Update related activity record, if exists and the approval statues has been changed
        if(!$this->getIsNewRecord()) {

            if($this->originalApprovalStatus != $this->approvalStatus) {
                $this->updateRelatedAmActivityStatus(AmActivity::TYPE_PRIVATE_MESSAGE);
            }
        }
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
            $criteria->addInCondition('review.businessId', $business);
        }
        else {
            $criteria->compare('review.businessId', $business);
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
     * @return array List of available message sources
     */
    public function getSources()
    {
        return array(
            self::SOURCE_DASHBOARD => 'Dashboard',
            self::SOURCE_YELP => 'Yelp',
        );
    }

    /**
     * Sets message source.
     * It does not change source if message has already a source.
     * @param integer $source Selected source
     */
    public function setSource($source)
    {
        if($this->source == 0) {
            $this->source = $source;
        }
    }

    /**
     * @return string Message date or generic label
     */
    public function getMessageDate()
    {
        if($this->messageDate == '0000-00-00 00:00:00') {
            return '';
        }
        else {
            return Yii::app()->getComponent('format')->formatDate($this->messageDate);
        }
    }

    /**
     * Find message by messageHash.
     * @param string $hash Message hash
     * @return object The found object. Null if object not found
     */
    public function findByMessageHash($hash)
    {
        return $this->findByAttributes(array('messageHash'=>$hash));
    }

    /**
     * @return boolean Whether message is actually a comment
     */
    public function isComment()
    {
        return $this->messageType == 'comment';
    }

    /**
     * @return boolean Whether message is sent by site owner staff
     */
    public function isStaffAnswer()
    {
        return $this->source == self::SOURCE_DASHBOARD;
    }

    /**
     * @return boolean Whether message is sent by biz owner.
     */
    public function isBizOwnerAnswer()
    {
        return $this->source == self::SOURCE_YELP && $this->from != $this->review->userName;
    }

    /**
     * @return boolean Whether review response is waiting for approval
     */
    public function isWaitingForApproval()
    {
        return $this->approvalStatus == self::APPROVAL_STATUS_WAITING && $this->isStaffAnswer();
    }

    /**
     * Mark messages as processed.
     * @param integer $reviewId Review id
     * @return integer the number of rows being updated
     */
    public function markAsProcessed($reviewId)
    {
        return $this->updateAll(array('processed'=>self::PROCESSED_TRUE), 'reviewId = :review', array(':review'=>$reviewId));
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
        );
    }

    public function approvePrivateMessage($changed = false)
    {
        // Add public comment action to phantom queue
        $queueModel = new Queue;

        if(!$queueModel->addToQueue('privateMessage', array($this->id))) {
            throw new CDbException("Review {$this->id} failed while adding private message to queue");
        }

        $this->approvalStatus = $changed ? self::APPROVAL_STATUS_CHANGED : self::APPROVAL_STATUS_ACCEPTED;

        //update approval status
        $this->review->approvalStatus = $this->approvalStatus;

        if(!$this->review->save()) {
            throw new CDbException('Review has not been saved');
        }

        return $this->save();
    }

    /**
     * Updated the status of the related activity record, if exists
     */
    public function updateRelatedAmActivityStatus()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('reviewId', $this->reviewId);
        $criteria->compare('messageId', $this->id);
        $criteria->compare('type', AmActivity::TYPE_PRIVATE_MESSAGE);

        $activity = AmActivity::model()->find($criteria);

        if($activity) {
            $activity->status = $this->approvalStatus;

            //If the activity comment exits, add it
            if(!empty($this->activityComment)) {
                $activity->comment = $this->activityComment;
            }

            $activity->save();
        }
    }
}
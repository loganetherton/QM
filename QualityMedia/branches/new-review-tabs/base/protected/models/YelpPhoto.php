<?php
/**
 * Model for individual Yelp photos
 *
 * The following fields are available in the table 'yelp_photos':
 *
 * @property integer $id
 * @property integer $businessId
 * @property integer $yelpBusinessId
 * @property string $photoId
 * @property integer $fromOwner
 * @property string $uploaderName
 * @property string $uploaderId
 * @property string $uploaderProfile
 * @property string $caption
 * @property string $photoUrl
 * @property string $actions
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $flagged
 * @property integer $uploaded
 * @property integer $deleted
 * @property integer $saved
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */
class YelpPhoto extends ActiveRecord
{
    const STATUS_FLAGGED    = 1;
    const STATUS_FROM_OWNER = 1;
    const STATUS_UPLOADED   = 1;
    const STATUS_DELETED    = 1;
    const STATUS_SAVED      = 1;

    /**
     * Returns the static object for this model
     *
     * @static
     * @access public
     * @param string $className
     * @return YelpPhoto
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the table name for this model
     *
     * @access public
     * @return string
     */
    public function tableName()
    {
        return 'yelp_photos';
    }

    /**
     * Returns basic validation rules for this model
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array(
            array('businessId, photoId, fromOwner, photoUrl', 'required'),
            array('businessId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('yelpBusinessId', 'exist', 'className'=>'YelpBusiness', 'attributeName'=>'id'),
            array('fromOwner, flagged, uploaded, deleted, saved', 'boolean'),
            array('caption, uploaderName, uploaderProfile, photoUrl, uploaderId', 'filter', 'filter'=>'trim'),
            array('uploaderName, uploaderId, photoId', 'length', 'max'=>100),
            array('uploaderProfile, photoUrl', 'length', 'max'=>255),
        );
    }

    /**
     * afterFind hook, called right after a row has been fetched using find
     *
     * @access protected
     * @return void
     */
    protected function afterFind()
    {
        parent::afterFind();

        $this->actions = explode(',', $this->actions);
    }

    /**
     * beforeValidate hook, called right before a record is validated
     *
     * @access protected
     * @return bool
     */
    protected function beforeValidate()
    {
        $this->actions = !empty($this->actions) ? implode(',', $this->actions) : '';

        return parent::beforeValidate();
    }

    /**
     * beforeSave hook, called right before a record is saved
     *
     * @access protected
     * @return bool
     */
    protected function beforeSave()
    {
        $this->saved = 0;

        return parent::beforeSave();
    }

    /**
     * Defines some scopes for this photos
     *
     * @access public
     * @return array
     */
    public function scopes()
    {
        return array(
            'flagged' => array(
                'condition' => 'flagged = :flagged',
                'params' => array(':flagged' => self::STATUS_FLAGGED),
            ),
            'notFlagged' => array(
                'condition' => 'flagged != :flagged',
                'params' => array(':flagged' => self::STATUS_FLAGGED),
            ),
            'owner' => array(
                'condition' => 'fromOwner = :flag',
                'params' => array(':flag' => self::STATUS_FROM_OWNER),
            ),
            'notOwner' => array(
                'condition' => 'fromOwner != :flag',
                'params' => array(':flag' => self::STATUS_FROM_OWNER),
            ),
            'uploaded' => array(
                'condition' => 'uploaded = :uploaded',
                'params' => array(':uploaded' => self::STATUS_UPLOADED),
            ),
            'notUploaded' => array(
                'condition' => 'uploaded != :uploaded',
                'params' => array(':uploaded' => self::STATUS_UPLOADED),
            ),
            'deleted' => array(
                'condition' => 'deleted = :deleted',
                'params' => array(':deleted' => self::STATUS_DELETED),
            ),
            'notDeleted' => array(
                'condition' => 'deleted != :deleted',
                'params' => array(':deleted' => self::STATUS_DELETED),
            ),
            'saved' => array(
                'condition' => 'saved = :saved',
                'params' => array(':saved' => self::STATUS_SAVED),
            ),
            'notSaved' => array(
                'condition' => 'saved != :saved',
                'params' => array(':saved' => self::STATUS_SAVED),
            ),
        );
    }

    /**
     * Defines some basic relations with other models
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array(
            'business' => array(self::BELONGS_TO, 'User', 'businessId'),
            'yelpBusiness' => array(self::BELONGS_TO, 'YelpBusiness', 'yelpBusinessId'),
        );
    }

    /**
     * Defines basic attribute labels for this model
     *
     * @access public
     * @return array
     */
    public function attribetuLabels()
    {
        return array(
            'id' => 'ID',
            'businessId' => 'Business ID',
            'yelpBusinessId' => 'Yelp Business',
            'photoId' => 'Photo ID',
            'photoUrl' => 'Photo URl',
            'fromOwner' => 'From Owner',
            'uploaderName' => 'Uploader Name',
            'uploaderId' => 'Uploader ID',
            'uploaderProfile' => 'Uploader Profile',
            'caption' => 'Caption',
            'actions' => 'Available actions',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'flagged' => 'Is flagged',
            'uploaded' => 'Is uploaded',
            'deleted' => 'Is deleted',
            'saved' => 'Is saved',
        );
    }

    /**
     * Business scope, allows to limit the photos to specified business(es)
     *
     * @access public
     * @param mixed $businesses
     * @return YelpPhoto
     */
    public function business($businesses)
    {
        if(is_array($businesses)) {
            $this->getDbCriteria()->addInCondition('businessId', $businesses);
        }
        else {
            $this->getDbCriteria()->compare('businessId', $businesses);
        }

        return $this;
    }

    /**
     * Yelp business id scope.
     * @param integer $yelpBusiness Yelp Business Id
     * @return object YelpPhoto
     */
    public function yelpBusinessScope($yelpBusiness)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'yelpBusinessId = :yelpBusiness',
            'params'=>array(':yelpBusiness'=>$yelpBusiness),
        ));

        return $this;
    }

    /**
     * Find model by yelpBusinessId.
     * @param string $yelpBusinessId Yelp business id
     * @return mixed The record found. Null if none is found.
     */
    public function findByYelpBusinessId($yelpBusinessId)
    {
        return $this->findByAttributes(array('yelpBusinessId'=>$yelpBusinessId));
    }

    /**
     * Returns whether this photo is flagged or not
     *
     * @access public
     * @return bool
     */
    public function isFromOwner()
    {
        return $this->fromOwner;
    }

    /**
     * Returns whether this photo is flagged or not
     *
     * @access public
     * @return bool
     */
    public function isFlagged()
    {
        return $this->flagged;
    }

    /**
     * Returns whether this photo is uploaded or not
     *
     * @access public
     * @return bool
     */
    public function isUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Returns whether this photo is deleted or not
     *
     * @access public
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Returns whether we can edit this photo's caption or not
     *
     * @access public
     * @return bool
     */
    public function canEditCaption()
    {
        return in_array('edit_caption', $this->actions);
    }

    /**
     * Returns whether we can delete this photo or not
     *
     * @access public
     * @return bool
     */
    public function canDelete()
    {
        return in_array('delete', $this->actions);
    }

    /**
     * Returns whether we can flag this photo or not
     *
     * @access public
     * @return bool
     */
    public function canFlag()
    {
        return in_array('flag', $this->actions);
    }

    /**
     * Mark photo as flagged.
     * @return boolean Whether the saving succeeds
     */
    public function markAsFlagged()
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            $this->flagged = self::STATUS_FLAGGED;

            if(!$this::save()) {
                throw new CDbException("Photo {$this->id} cannot be flagged");
            }

            // Add flag action to phantom queue
            $queueModel = new Queue;
            if(!$queueModel->addToQueue('savePhotos', array($this->businessId, $this->yelpBusinessId))) {
                throw new CDbException("Photo {$this->id} failed while adding to queue");
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
     * Update photo's caption.
     * @return boolean Whether the saving succeeds
     */
    public function updateCaption()
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            if(!$this::update(array('caption', 'saved', 'updatedAt'))) {
                throw new CDbException("Photo {$this->id} caption cannot be changed");
            }

            // Add caption update to phantom queue
            $queueModel = new Queue;
            if(!$queueModel->addToQueue('savePhotos', array($this->businessId, $this->yelpBusinessId))) {
                throw new CDbException("Photo {$this->id} failed while adding to queue");
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
     * Handles deleting the row
     *
     * @access public
     * @param bool $force
     * @return boolean Whether the deletion is successful
     */
    public function delete($force = false)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            if(!$this->uploaded) {
                @unlink($this->photoUrl);
            }

            if($this->uploaded && !$force) {
                $this->deleted = self::STATUS_DELETED;

                if(!$this->save()) {
                    throw new CDbException("Photo {$this->id} could not been deleted");
                }
            }
            else {
                if(!parent::delete()) {
                    throw new CDbException("Photo {$this->id} could not been deleted");
                }
            }

            // Add caption update to phantom queue
            $queueModel = new Queue;
            if(!$queueModel->addToQueue('savePhotos', array($this->businessId, $this->yelpBusinessId))) {
                throw new CDbException("Deleted photo {$this->id} failed while adding to queue");
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
}
<?php
/**
 * Model for individual Yelp photos
 *
 * The following fields are available in the table 'yelp_photos':
 *
 * @property int $id
 * @property int $businessId
 * @property string $photoId
 * @property bool $fromOwner
 * @property string $uploaderName
 * @property string $uploaderId
 * @property string $uploaderProfile
 * @property string $caption
 * @property string $photoUrl
 * @property string $actions
 * @property datetime $createdAt
 * @property datetime $updatedAt
 * @property bool $flagged
 * @property bool $uploaded
 * @property bool $deleted
 * @property bool $saved
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpPhoto extends ActiveRecord
{
    const STATUS_FLAGGED = 1;
    const STATUS_FROM_OWNER = 1;
    const STATUS_UPLOADED = 1;
    const STATUS_DELETED = 1;
    const STATUS_SAVED = 1;

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
            array('businessId', 'exists', 'className' => 'User'),
            array('fromOwner, flagged, uploaded, deleted, saved', 'boolean'),
            array('caption, uploaderName, uploaderProfile, photoUrl, uploaderId', 'filter', 'filter' => 'trim'),
            array('uploaderName, uploaderId, photoId', 'length', 'max' => 100),
            array('uploaderProfile, photoUrl', 'length', 'max' => 255),
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
        $this->actions = explode(',', $this->actions);
        parent::afterFind();
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
        if (is_array($businesses))
            $this->getDbCriteria()->addInCondition('businessId', $businesses);
        else
            $this->getDbCriteria()->compare('businessId', $businesses);

        return $this;
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
     * Handles deleting the row
     *
     * @access public
     * @param bool $force
     * @return void
     */
    public function delete($force = false)
    {
        if (!$this->uploaded)
            @unlink($this->photoUrl);

        if ($this->uploaded && !$force)
        {
            $this->deleted = 1;
            $this->save();
        }
        else
            parent::delete();
    }
}
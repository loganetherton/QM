<?php
/**
 * This is the model class for table "yelp_businesses".
 *
 * The followings are the available columns in table 'yelp_businesses':
 * @property integer $id
 * @property integer $profileId
 * @property integer $userId
 * @property string $yelpId
 * @property string $bizId
 * @property integer reviewsCount
 * @property integer responsesWritten
 * @property string $label
 * @property integer $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Profile $profile
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpBusiness extends ActiveRecord
{
    const YELP_PUBLIC_BASE_URL = 'http://yelp.com/biz/';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return YelpBusiness the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'yelp_businesses';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('profileId, userId, yelpId, bizId, label', 'required'),
            array('profileId', 'exist', 'className'=>'Profile', 'attributeName'=>'id'),
            array('userId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('yelpId, bizId', 'length', 'max'=>100),
            array('label', 'length', 'max'=>255),
            array('reviewsCount, responsesWritten', 'numerical', 'integerOnly'=>true),
            array('status', 'boolean'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'profile' => array(self::BELONGS_TO, 'Profile', 'profileId'),

            'reviews' => array(self::HAS_MANY, 'Review', 'yelpBusinessId'),
            'emailReport' => array(self::HAS_MANY, 'EmailReport', 'yelpBusinessId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'profileId' => 'Profile',
            'userId' => 'User',
            'yelpId' => 'Yelp ID',
            'bizId' => 'Business ID',
            'reviewsCount' => 'Reviews Count',
            'responsesWritten' => 'Responses Written',
            'label' => 'Label',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'withCredentials'=>array(
                'with'=>'profile',
                'together'=>true,
            ),
        );
    }

    /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave()
    {
        parent::afterSave();

        if($this->getIsNewRecord() === true) {
            $data = array('id'=>$this->id);

            WorkerActiveTask::scheduleTask('PhantomJsReadReviewsWorker', $data);
            WorkerActiveTask::scheduleTask('PhantomJsReadAnalyticsWorker', $data);
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Find by yelp id.
     * @param string $yelpId Yelp business id
     * @return object The record found. Null if none is found
     */
    public function findByYelpId($yelpId)
    {
        return $this->findByAttributes(array('yelpId'=>$yelpId));
    }

    /**
     * Find by biz id.
     * @param string $bizId Yelp business id (hash)
     * @return object The record found. Null if none is found
     */
    public function findByBizId($bizId)
    {
        return $this->findByAttributes(array('bizId'=>$bizId));
    }

    /**
     * @return boolean Whether business is active
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * Increment reviews count.
     * @return boolean Whether the saving succeeds
     */
    public function incrementReviewsCount()
    {
        $this->reviewsCount++;

        return $this->save();
    }

    /**
     * Increment responses written.
     * @return boolean Whether the saving succeeds
     */
    public function incrementResponsesWritten()
    {
        $this->responsesWritten++;

        return $this->save();
    }

    /**
     * @return string Yelp profile's public link
     */
    public function getYelpProfileLink()
    {
        return self::YELP_PUBLIC_BASE_URL . $this->yelpId;
    }
}
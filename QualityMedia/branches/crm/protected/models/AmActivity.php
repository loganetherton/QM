<?php

/**
 * This is the model class for table "am_activities".
 *
 * The followings are the available columns in table 'am_activities':
 * @property string $id
 * @property string $businessId
 * @property string $accountManagerId
 * @property string $type
 * @property integer $status
 * @property string $comment
 * @property string $createdAt
 * @property string $updatedAt
 *
 * Related Object Status
 *
 * @property integer $relatedStatus
 */
class AmActivity extends ActiveRecord
{

    public $dateRange = null;

    public $relatedStatus = null;

    const TYPE_PUBLIC_COMMENT = 'publicComment';
    const TYPE_PRIVATE_MESSAGE = 'privateMessage';
    const TYPE_FLAG = 'flag';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AmActivity the static model class
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
        return 'am_activities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('businessId, accountManagerId, reviewId, status', 'required'),
            array('status', 'numerical', 'integerOnly'=>true),
            array('businessId, accountManagerId, reviewId, messageId', 'length', 'max'=>11),
            array('type', 'length', 'max'=>40),
            array('comment', 'length', 'max'=>255),
            array('createdAt, updatedAt', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, businessId, accountManagerId, reviewId, type, status, comment, createdAt, updatedAt, dateRange', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'businessId'),
            'review' => array(self::BELONGS_TO, 'Review', 'reviewId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'businessId' => 'Business',
            'accountManagerId' => 'Account Manager',
            'type' => 'Type',
            'status' => 'Status',
            'comment' => 'Comment',
            'createdAt' => 'Action Date',
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

        //Related Object Approval status
        switch($this->type) {
            case self::TYPE_PUBLIC_COMMENT:
                $this->relatedStatus = $this->review->approvalStatus;
            break;
            case self::TYPE_PRIVATE_MESSAGE:
                $message = $this->getMessage();

                if($message == null) {
                    $this->relatedStatus = $this->status;
                }
                else {
                    $this->relatedStatus = $message->approvalStatus;
                }
            break;
            case self::TYPE_FLAG:
                $this->relatedStatus = $this->review->flagApprovalStatus;
            break;
            default:
                $this->relatedStatus = 0;
            break;
        }
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'flags'=>array(
                'condition' => 't.type like "flag"'
            ),
            'publicComments'  =>array(
                'condition' => 't.type like "publicComment"'
            ),
            'privateMessages'  =>array(
                'condition' => 't.type like "privatMessage"'
            ),
        );
    }

    /**
    * Retrieves a list of models based on the current search/filter conditions.
    * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
    */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = array(
            'user',
            'user.billingInfo'=>array(
                'select'=>'companyName',
                'together'=>true,
            )
        );

        $criteria->compare('businessId',$this->businessId,true);
        $criteria->compare('t.accountManagerId',$this->accountManagerId);
        // $criteria->compare('type',$this->type,true);
        if(!empty($this->dateRange)) {
            $dateRange = explode(' - ', $this->dateRange);
            $criteria->addBetweenCondition('t.createdAt', date('Y-m-d', strtotime($dateRange[0])), date('Y-m-d', strtotime($dateRange[1])));
        }

        $result = new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.createdAt DESC, t.id ASC',
            ),
        ));

        return $result;
    }

    public function getActionTypesLabels()
    {
        return array(
            'publicComment' => 'Public Comment',
            'flag' => 'Flag',
            'privateMessage' => 'Private Message'
        );
    }

    public function clientsDropDownList()
    {
        return AccountManager::model()->findByPk($this->accountManagerId)->clientsDropDownList();
    }

    /**
     * Add new Am activity
     * @param [type]  $businessId       client id
     * @param [type]  $accountManagerId Account Manager id
     * @param [type]  $type             Type of an activity
     * @param integer $status           Related object approval status
     * @param [type]  $comment          custom comment
     */
    public function addActivity($accountManagerId, $businessId, $reviewId, $type, $status = 0, $comment = '', $messageId = null)
    {

        $attributes = array(
            'businessId'       => (int) $businessId,
            'accountManagerId' => (int) $accountManagerId,
            'reviewId'         => (int) $reviewId,
            'messageId'         => (int) $messageId,
            'type'             => $type,
            'status'           => $status,
            'comment'          => $comment,
        );

        $this->setAttributes($attributes);
        return $this->save();
    }

    /**
     * Returns Types List
     */
    public function getTypes() {
        return array(
            'Public Comment' => self::TYPE_PUBLIC_COMMENT,
            'Private Message' => self::TYPE_PRIVATE_MESSAGE,
            'Flag' => self::TYPE_FLAG
        );
    }

    /**
     * Returns Activities total counts separated by type
     * @param CDBCriteria $criteria
     * @return array Total Counts list
     */
    public function getTotals($criteria)
    {
        $totals = array();

        foreach($this->getTypes() as $label => $type) {
            $typeCriteria = clone $criteria;

            $typeCriteria->compare('type', $type, true);
            $totals[$label] = (int) $this->count($typeCriteria);
        }

        return $totals;
    }

    /**
     * Returns related Message instance
     * @return Message Message model
     */
    public function getMessage()
    {
        if(empty($this->messageId)) {
            return null;
        }

        $message = Message::model()->findByPk($this->messageId);
        return $message;
    }
}
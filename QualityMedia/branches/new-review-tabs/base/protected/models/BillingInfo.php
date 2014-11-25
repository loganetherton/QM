<?php
/**
 * This is the model class for table "billing_info".
 *
 * The followings are the available columns in table 'billing_info':
 * @property integer $id
 * @property integer $userId
 * @property string $firstName
 * @property string $lastName
 * @property string $companyName
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $zipCode
 * @property string $state
 * @property string $country
 * @property string $phone
 * @property string $ipAddress
 * @property integer $last4digits
 * @property string $cardType
 * @property string $contractDate
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class BillingInfo extends ActiveRecord
{

    /**
     * @var array $dateAttributes Timestamp attributes.
     */
    protected $dateAttributes = array(
         'contractDate',
    );

    /**
     * @var string $originalContractDate Original Contract Dates to recognize if changed on save.
     */
    protected $originalContractDate;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BillingInfo the static model class
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
        return 'billing_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('userId, firstName, lastName, companyName', 'required'),
            array('userId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('last4digits', 'numerical', 'integerOnly'=>true),
            array('firstName, lastName, city, state', 'length', 'max'=>100),
            array('companyName', 'length', 'max'=>250),
            array('address1, address2', 'length', 'max'=>200),
            array('country', 'length', 'max'=>2),
            array('zipCode', 'length', 'max'=>10),
            array('phone', 'length', 'max'=>30),
            array('ipAddress', 'length', 'max'=>15),
            array('cardType', 'length', 'max'=>20),
            array('contractDate', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss', 'allowEmpty'=>true),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'companyName' => 'Company Name',
            'address1' => 'Address',
            'address2' => 'Address',
            'city' => 'City',
            'zipCode' => 'Zip Code',
            'state' => 'State',
            'country' => 'Country',
            'phone' => 'Phone Number',
            'ipAddress' => 'Ip Address',
            'last4digits' => 'Last4digits',
            'cardType' => 'Card Type',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',

            'creditCard' => 'Credit Card',
        );
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        parent::afterFind();

        $this->originalContractDate = $this->contractDate;

        $this->cleanUpDates();
    }

    /**
     * This method is invoked after saving a record successfully.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave()
    {
        parent::afterSave();

        //Update Precontract reviews
        if(!$this->getIsNewRecord() && $this->originalContractDate != $this->contractDate) {
            $this->recalculatePrecontractReviews();
        }

    }

    /**
     * This method is invoked before validation starts.
     * The default implementation calls {@link onBeforeValidate} to raise an event.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return boolean whether validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    protected function beforeValidate()
    {
        // Clean up dates so empty values can pass validation
        $this->cleanUpDates();

        return parent::beforeValidate();
    }

    /**
     * User scope.
     * @param integer $userId User id
     * @return object BillingInfo
     */
    public function userScope($userId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'userId = :user',
            'params'=>array(':user'=>(int)$userId),
        ));

        return $this;
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
     * Find model by user(client) id.
     * @param string $userId user(client) id
     * @return mixed The record found. Null if none is found.
     */
    public function findByUserId($userId)
    {
        return $this->findByAttributes(array('userId'=>$userId));
    }

    /**
     * Returns blacked-out credit card number.
     * @return string Blacked-out credit card number
     */
    public function getCreditCard()
    {
        return sprintf('**** **** **** %04d', $this->last4digits);
    }

    /**
     * Handle recurly push notification.
     * @param object $event CEvent object
     * @return boolean True if everything is ok
     * @throws CDbException if model has not been saved
     */
    public function handlePushNotification($event)
    {
        $billingInfo = $event->sender->billingInfo;

        if($billingInfo === null) {
            Yii::log('BillingInfo push notification handler: billingInfo is empty', CLogger::LEVEL_INFO, 'push');

            return true;
        }

        $account = $event->sender->account;

        // User object has been added in User::handlePushNotification method
        $user = $event->params['user'];

        $model = $user->billingInfo !== null ? $user->billingInfo : new self;

        $model->setAttributes(array(
            'userId'        => $user->id,
            'firstName'     => (string)$billingInfo->first_name,
            'lastName'      => (string)$billingInfo->last_name,
            'companyName'   => (string)$account->company_name,
            'address1'      => (string)$billingInfo->address1,
            'address2'      => (string)$billingInfo->address2,
            'city'          => (string)$billingInfo->city,
            'state'         => (string)$billingInfo->state,
            'zipCode'       => (string)$billingInfo->zip,
            'country'       => (string)$billingInfo->country,
            'phone'       => (string)$billingInfo->phone,
            'ipAddress'     => (string)$billingInfo->ip_address,
            'last4digits'   => (string)$billingInfo->last_four,
            'cardType'      => (string)$billingInfo->card_type,
        ));

        if(!$model->save()) {
            Yii::log(var_export($model->getErrors(), true), CLogger::LEVEL_ERROR, 'push');
            throw new CDbException('Billing info model has not been saved');
        }

        return true;
    }

    /**
     * Converts the input value to a timestamp format
     * @param  string $value custom date
     * @return
     */
    public function convertToTimestamp($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Change empty dated to null instead of "0000-00-00 00:00:00".
     */
    protected function cleanUpDates()
    {
        foreach($this->dateAttributes as $attribute) {
            if($this->$attribute == '0000-00-00 00:00:00') {
                $this->$attribute = null;
            }
        }
    }

    /**
     * Updates precontract reviews
     * @return boolean Whether the update suceeded
     */
    public function recalculatePrecontractReviews()
    {
        return Review::model()->recalculatePrecontractReviews($this->userId, $this->contractDate);
    }

}
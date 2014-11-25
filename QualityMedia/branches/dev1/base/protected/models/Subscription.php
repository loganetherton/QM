<?php
/**
 * This is the model class for table "subscriptions".
 *
 * The followings are the available columns in table 'subscriptions':
 * @property integer $id
 * @property integer $userId
 * @property string $uuid
 * @property string $planCode
 * @property string $planName
 * @property string $state
 * @property integer $unitAmount
 * @property integer $quantity
 * @property string $currency
 * @property string $addonsDetails
 * @property integer $addonsTotalAmount
 * @property string $activatedAt
 * @property string $canceledAt
 * @property string $expiresAt
 * @property string $currentPeriodStartedAt
 * @property string $currentPeriodEndsAt
 * @property string $trialStartedAt
 * @property string $trialEndsAt
 * @property string $startedAt
 * @property string $createdAt
 * @property string $updatedAt
 *
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Subscription extends ActiveRecord
{
    /**
     * @var integer $total Number of total subscriptions.
     */
    public $total = 0;

    /**
     * @var integer $attrition Salesman attrition.
     */
    public $attrition = 0;

    /**
     * @var array $dateAttributes Timestamp attributes.
     */
    protected $dateAttributes = array(
         'activatedAt', 'canceledAt', 'expiresAt',
         'currentPeriodStartedAt', 'currentPeriodEndsAt',
         'trialStartedAt', 'trialEndsAt',
    );

    /**
     * @var oldAttributes
     */

    protected $oldAttributes = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Subscription the static model class
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
        return 'subscriptions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('userId, uuid, planCode, planName, state, unitAmount, quantity, currency', 'required'),
            array('userId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('unitAmount, quantity, addonsTotalAmount', 'numerical', 'integerOnly'=>true),
            array('uuid', 'length', 'is'=>32),
            array('planCode, planName', 'length', 'max'=>200),
            array('state', 'length', 'max'=>10),
            array('currency', 'length', 'max'=>3),
            array('planCode, planName, state', 'safe', 'on'=>'search'),
            array('startedAt, addonsDetails', 'safe'),
            array('activatedAt, canceledAt, expiresAt, currentPeriodStartedAt, currentPeriodEndsAt, trialStartedAt, trialEndsAt', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss', 'allowEmpty'=>true),
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
            'uuid' => 'Uuid',
            'planCode' => 'Plan Code',
            'planName' => 'Plan Name',
            'state' => 'State',
            'unitAmount' => 'Unit Amount',
            'quantity' => 'Quantity',
            'currency' => 'Currency',
            'addonsDetails' => 'Addons Details',
            'addonsTotalAmount' => 'Addons Total Amount',
            'activatedAt' => 'Activated At',
            'canceledAt' => 'Canceled At',
            'expiresAt' => 'Expires At',
            'currentPeriodStartedAt' => 'Current Period Started At',
            'currentPeriodEndsAt' => 'Current Period Ends At',
            'trialStartedAt' => 'Trial Started At',
            'trialEndsAt' => 'Trial Ends At',
            'startedAt' => 'Started At',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',

            'user.salesman.fullName' => 'Salesman',
            'user.accountManager.fullName' => 'Account Manager',
        );
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'state = :state',
                'params'=>array(':state'=>'active'),
            ),
            'canceled'=>array(
                'condition'=>'state = :state',
                'params'=>array(':state'=>'canceled'),
            ),
        );
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
     * This method is invoked before saving a record (after validation, if any).
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        // Set null attributes to "empty" timestamp. Null updates timestamp columns with current date
        foreach($this->dateAttributes as $attribute) {
            if($this->$attribute == null) {
                $this->$attribute = '0000-00-00 00:00:00';
            }
        }

        //Detect reactivation
        if($this->isNewRecord) {
            $this->startedAt = $this->activatedAt;
        }
        else {
            switch(true) {
                case ($this->oldAttributes->state != 'active' && $this->state == 'active'):
                    $this->startedAt = date('Y-m-d H:i:s');
                break;
            }
        }

        return parent::beforeSave();
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        $this->oldAttributes = clone $this;
        parent::afterFind();

        $this->cleanUpDates();
    }

    /**
     * User scope.
     * @param integer $userId User id
     * @return object Subscription
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
     * Sales rep scope.
     * @param integer $salesmanId Salesman id
     * @return object Subscription
     */
    public function salesmanScope($salesmanId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'user.salesmanId = :salesman',
            'params'=>array(':salesman'=>$salesmanId),
        ));

        return $this;
    }

    /**
     * @return array List of subscription statuses
     */
    public function getStatuses()
    {
        return array(
            'active'   => 'Active Subscriptions',
            'canceled' => 'Canceled Subscriptions',
            'expired'  => 'Expired Subscriptions',
            'future'  => 'Future Subscriptions'
        );
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
     * fix the dates with wrong timezone
     * @param  boolean $startedAt set to fix also startedAt field
     */
    public function fixDates($startedAt = false) {
        Yii::app()->getComponent('recurly');
        $subscription = Recurly_Subscription::get($this->uuid);
        $attributes = array();

        // Set date attributes. Not all attributes are available
        $fields = array(
            'activatedAt'   => 'activated_at',
            'canceledAt'    => 'canceled_at'
,           'expiresAt'     => 'expires_at',
            'trialStartedAt'=> 'trial_started_at',
            'trialEndsAt'   => 'trial_ends_at',
            'currentPeriodStartedAt'=> 'current_period_started_at',
            'currentPeriodEndsAt'   => 'current_period_ends_at',
        );

        $timeZone = new DateTimeZone(date_default_timezone_get());

        foreach($fields as $attribute => $field) {
            if(isset($subscription->{$field})) {
                $attributes[$attribute] = $subscription->{$field}->setTimezone($timeZone)->format('Y-m-d H:i:s');
            }
        }

        //repair startedAt fiel
        if($startedAt) {
            $attributes['startedAt'] = $this->createdAt;
        }

        $this->setAttributes($attributes);
        return $this->save();
    }

    /**
     * Returns subscription by it's uuid.
     * @param string $uuid Subscription UUID
     * @return object CActiveRecord The record found. Null if none is found.
     */
    public function findByUuid($uuid)
    {
        return $this->findByAttributes(array('uuid'=>$uuid));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('user', 'user.billingInfo', 'user.salesman', 'user.accountManager');

        //@TODO fix this part

        $criteria->compare('planCode', $this->planCode,true);
        $criteria->compare('billingInfo.companyName', $this->planCode,true, 'OR');
        if(!empty($this->state)) {
            $criteria->addInCondition('t.state',(array)$this->state);
        }

        $criteria->together = true;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>array(
                    'startedAt'=>CSort::SORT_DESC,
                ),
                'attributes'=>array(
                    'user.email'=>array(
                        'asc'=>'user.email',
                        'desc'=>'user.email DESC',
                    ),
                    'user.billingInfo.companyName'=>array(
                        'asc'=>'billingInfo.companyName',
                        'desc'=>'billingInfo.companyName DESC',
                    ),
                    'user.billingInfo.firstName'=>array(
                        'asc'=>'billingInfo.firstName',
                        'desc'=>'billingInfo.firstName DESC',
                    ),
                    'user.billingInfo.lastName'=>array(
                        'asc'=>'billingInfo.lastName',
                        'desc'=>'billingInfo.lastName DESC',
                    ),
                    'user.salesman.fullName'=>array(
                        'asc'=>'salesman.lastName, salesman.firstName',
                        'desc'=>'salesman.lastName DESC, salesman.firstName DESC',
                    ),
                    'user.accountManager.fullName'=>array(
                        'asc'=>'accountManager.lastName, accountManager.firstName',
                        'desc'=>'accountManager.lastName DESC, accountManager.firstName DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * @return boolean Whether subscription is active
     */
    public function isActive()
    {
        return $this->state == 'active';
    }

    /**
     * @return boolean Whether subscription is future
     */
    public function isFuture()
    {
        return $this->state == 'future';
    }

    /**
     * Returns subscription amount in dollars (not cents).
     * @return float Subscription amount
     */
    public function getAmount()
    {
        return $this->unitAmount/100;
    }

    /**
     * Returns subscription addons amount in dollars (not cents).
     * @return float Addons Total amount
     */
    public function getAddonsTotalAmount()
    {
        return $this->addonsTotalAmount/100;
    }

    /**
     * Returns info if the subscription has addons or not
     * @return boolean
     */
    public function hasAddons()
    {
        if((boolean) $this->addonsTotalAmount && count((array)CJSON::decode($this->addonsDetails))) {
            return true;
        }

        return false;
    }

    /**
     * Returns Subscription Plan related setup fee
     * @return int Setup fee in centes
     */
    public function getSetupFee()
    {
        $plan = Plan::model()->findByPlanCode($this->planCode);
        return $plan->setupFee;
    }

    /**
     * Returns full plan description including addons and setup fee
     * @param  boolean $setupFee [description]
     * @return [type]            [description]
     */
    public function getPlanDescription($setupFee = false)
    {
        $description = $this->planName;

        //Add setup Fee to the description
        if($setupFee && !strstr($this->planName, 'setup fee')) {

            $plan = Plan::model()->findByPlanCode($this->planCode);

            $description .= sprintf(' + $%s one time setup fee', $plan->setupFee / 100);
        }

        if($this->hasAddons()) {
            $addons = CJSON::decode($this->addonsDetails);

            if(!count((array) $addons)) {
                return $description;
            }

            foreach($addons as $addon) {
                $description .= ' + ';

                if(strstr($addon['addOnCode'], 'email-marketing')) {
                    $description .= '$'.($addon['unitAmount'] / 100).' Email Campaign';
                }
                else {
                    $description .= $addon['name'];
                }
            }
        }

        return $description;
    }

    /**
     * Returns list of subscription numbers over last X months.
     * @param integer $months Months offset
     * @return array List of subscription numbers
     */
    public function getSubscriptionsPerMonth($months = 6)
    {
        $offset = date('Y-m-d H:i:s', strtotime("now -{$months} months"));

        $criteria = new CDbCriteria;

        $criteria->condition = 'createdAt > :offset';
        $criteria->params = array(':offset'=>$offset);

        $subscriptions = $this->active()->findAll($criteria);

        // Create an array with empty values for the last X months
        $results = array();
        for($i = 0; $i < $months + 1; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $results[date('m/Y', $timestamp)] = 0;
        }

        foreach($subscriptions as $subscription) {
            $label = date('m/Y', strtotime($subscription->createdAt));

            $results[$label] = $results[$label]+1;
        }

        return array_reverse($results);
    }

    /**
     * @return array List of 10 salesmen with lowest attrition.
     */
    public function getSalesmenAttrition()
    {
        // This is the query that is being used. It uses self join
        // SELECT *, (COUNT(t2.id) / COUNT(t.id)) * 100 AS attrition
        // FROM subscriptions t
        // LEFT JOIN subscriptions t2 ON t.id = t2.id AND t2.state = 'canceled'
        // LEFT JOIN users user ON t.userId = u.id
        // LEFT JOIN salesmen salesman ON user.salesmanId = salesman.id
        // WHERE salesman.id IS NOT NULL
        // GROUP BY salesman.id
        // ORDER BY attrition

        $criteria = new CDbCriteria;

        $criteria->with = array('user','user.salesman');
        $criteria->together = true;

        $criteria->select = array('(COUNT(t2.id) / COUNT(t.id)) * 100 AS attrition');
        $criteria->join = 'LEFT JOIN subscriptions t2 ON t.id = t2.id AND t2.state != "active"';

        $criteria->condition = 'salesman.id IS NOT NULL';
        $criteria->group = 'salesman.id';
        $criteria->order = 'attrition';

        return $this->findAll($criteria);
    }

    /**
     * Cancel subscription.
     * @return boolean Whether subscription has been canceled
     */
    public function cancelSubscription()
    {
        Yii::app()->getComponent('recurly')->cancelSubscription($this->uuid);

        $this->state = 'canceled';

        return $this->save();
    }

    /**
     * Handle recurly push notification.
     * @param object $event CEvent object
     */
    public function handlePushNotification($event)
    {
        $subscription = $event->sender->subscription;

        if($subscription === null) {
            $event->params['subscription'] = null;

            return false;
        }

        // User object has been added in User::handlePushNotification method
        $user = $event->params['user'];

        $model = $this->findByUuid($subscription->uuid);

        if($model === null) {
            $model = new self;
        }

        $attributes = array(
            'userId'        => $user->id,
            'uuid'          => (string)$subscription->uuid,
            'planCode'      => (string)$subscription->plan->plan_code,
            'planName'      => (string)$subscription->plan->name,
            'state'         => (string)$subscription->state,
            'unitAmount'    => (int)$subscription->unit_amount_in_cents,
            'quantity'      => (int)$subscription->quantity,
            'currency'      => (string)$subscription->currency,
        );

        // Set date attributes. Not all attributes are available
        $fields = array(
            'activatedAt'   => 'activated_at',
            'canceledAt'    => 'canceled_at',
            'expiresAt'     => 'expires_at',
            'trialStartedAt'=> 'trial_started_at',
            'trialEndsAt'   => 'trial_ends_at',
            'currentPeriodStartedAt'=> 'current_period_started_at',
            'currentPeriodEndsAt'   => 'current_period_ends_at',
        );

        $timeZone = new DateTimeZone(date_default_timezone_get());

        foreach($fields as $attribute => $field) {
            if(isset($subscription->{$field})) {
                $attributes[$attribute] = $subscription->{$field}->setTimezone($timeZone)->format('Y-m-d H:i:s');
            }
        }

        //addons handling
        $addOns = array();
        $addOnsTotal = 0;

        $addOnFields = array(
            'addOnCode' => 'add_on_code',
            'unitAmount' => 'unit_amount_in_cents',
            'quantity' => 'quantity',
        );

        $addOnAttributes = array();

        //get addons names
        $addonsList = Recurly_AddonList::get($subscription->plan->plan_code);
        $addonsNames = array();

        //parse addons
        foreach ($addonsList as $recurlyAddon) {
            $addonsNames[$recurlyAddon->add_on_code] = $recurlyAddon->name;
        }

        //parse addons
        if(isset($subscription->subscription_add_ons) && count((array) $subscription->subscription_add_ons)) {

            foreach($subscription->subscription_add_ons as $addOn) {

                foreach($addOnFields as $addOnAttribute => $addOnField) {
                    if(isset($addOn->{$addOnField})) {
                        $addOnAttributes[$addOnAttribute] = $addOn->{$addOnField};
                    }
                }

                $addOnAttributes['name'] = $addonsNames[$addOnAttributes['addOnCode']];

                $addOns[$addOnAttributes['addOnCode']] = $addOnAttributes;

                $addOnsTotal += $addOnAttributes['unitAmount'];
            }
        }

        $attributes['addonsTotalAmount'] = $addOnsTotal;
        $attributes['addonsDetails'] = CJSON::encode($addOns);

        $model->setAttributes($attributes);

        if(!$model->save()) {
            throw new CDbException('Subscription model has not been saved');
        }

        // Store subscription model as event params (for transactions).
        $event->params['subscription'] = $model;

        return true;
    }

    /**
     * Save a new subscription from recurly object.
     * @param object $event CEvent object
     */
    public function saveFromRecurlyObject($subscription, $userId)
    {

        $model = $this->findByUuid($subscription->uuid);

        if($model === null) {
            $model = new self;
        }

        $attributes = array(
            'userId'        => $userId,
            'uuid'          => (string)$subscription->uuid,
            'planCode'      => (string)$subscription->plan->plan_code,
            'planName'      => (string)$subscription->plan->name,
            'state'         => (string)$subscription->state,
            'unitAmount'    => (int)$subscription->unit_amount_in_cents,
            'quantity'      => (int)$subscription->quantity,
            'currency'      => (string)$subscription->currency,
        );

        // Set date attributes. Not all attributes are available
        $fields = array(
            'activatedAt'   => 'activated_at',
            'canceledAt'    => 'canceled_at',
            'expiresAt'     => 'expires_at',
            'trialStartedAt'=> 'trial_started_at',
            'trialEndsAt'   => 'trial_ends_at',
            'currentPeriodStartedAt'=> 'current_period_started_at',
            'currentPeriodEndsAt'   => 'current_period_ends_at',
        );

        $timeZone = new DateTimeZone(date_default_timezone_get());

        foreach($fields as $attribute => $field) {
            if(isset($subscription->{$field})) {
                $attributes[$attribute] = $subscription->{$field}->setTimezone($timeZone)->format('Y-m-d H:i:s');
            }
        }

        //addons handling
        $addOns = array();
        $addOnsTotal = 0;

        $addOnFields = array(
            'addOnCode' => 'add_on_code',
            'unitAmount' => 'unit_amount_in_cents',
            'quantity' => 'quantity',
        );

        $addOnAttributes = array();

        //get addons names
        $addonsList = Recurly_AddonList::get($subscription->plan->plan_code);
        $addonsNames = array();

        //parse addons
        foreach ($addonsList as $recurlyAddon) {
            $addonsNames[$recurlyAddon->add_on_code] = $recurlyAddon->name;
        }

        //parse addons
        if(isset($subscription->subscription_add_ons) && count((array) $subscription->subscription_add_ons)) {

            foreach($subscription->subscription_add_ons as $addOn) {

                foreach($addOnFields as $addOnAttribute => $addOnField) {
                    if(isset($addOn->{$addOnField})) {
                        $addOnAttributes[$addOnAttribute] = $addOn->{$addOnField};
                    }
                }

                $addOnAttributes['name'] = $addonsNames[$addOnAttributes['addOnCode']];

                $addOns[$addOnAttributes['addOnCode']] = $addOnAttributes;

                $addOnsTotal += $addOnAttributes['unitAmount'];
            }
        }

        $attributes['addonsTotalAmount'] = $addOnsTotal;
        $attributes['addonsDetails'] = CJSON::encode($addOns);

        $model->setAttributes($attributes);

        if(!$model->save()) {
            throw new CDbException('Subscription has not been saved');
        }

        return true;
    }
}
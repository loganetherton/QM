<?php
/**
 * This is the model class for table "transactions".
 *
 * The followings are the available columns in table 'transactions':
 * @property integer $id
 * @property integer $userId
 * @property integer $subscriptionId
 * @property string $uuid
 * @property string $action
 * @property string $currency
 * @property integer $amountInCents
 * @property integer $taxtInCents
 * @property string $status
 * @property string $source
 * @property integer $test
 * @property integer $voidable
 * @property integer $refundable
 * @property string $cvvResult
 * @property string $avsResult
 * @property string $avsResultStreet
 * @property string $avsResultPostal
 * @property string $transactionDate
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Subscription $subscription
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Transaction extends ActiveRecord
{
    /**
     * @var float $total Total revenue value.
     */
    public $total = 0.00;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Transaction the static model class
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
        return 'transactions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('userId, uuid, action, currency, amountInCents, taxtInCents, status, source, test, voidable, refundable', 'required'),
            array('userId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('subscriptionId', 'exist', 'className'=>'Subscription', 'attributeName'=>'id'),
            array('amountInCents, taxtInCents, test, voidable, refundable', 'numerical', 'integerOnly'=>true),
            array('uuid', 'length', 'is'=>32),
            array('action, source', 'length', 'max'=>15),
            array('currency', 'length', 'is'=>3),
            array('status', 'length', 'max'=>10),
            array('cvvResult, avsResult, avsResultStreet, avsResultPostal', 'length', 'max'=>100),
            array('transactionDate', 'safe'),
            array('status', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user'=>array(self::BELONGS_TO, 'User', 'userId'),
            'subscription' => array(self::BELONGS_TO, 'Subscription', 'subscriptionId'),
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
            'subscriptionId' => 'Subscription',
            'uuid' => 'Uuid',
            'action' => 'Action',
            'currency' => 'Currency',
            'amountInCents' => 'Amount',
            'taxtInCents' => 'Taxt In Cents',
            'status' => 'Status',
            'source' => 'Source',
            'test' => 'Test',
            'voidable' => 'Voidable',
            'refundable' => 'Refundable',
            'cvvResult' => 'Cvv Result',
            'avsResult' => 'Avs Result',
            'avsResultStreet' => 'Avs Result Street',
            'avsResultPostal' => 'Avs Result Postal',
            'transactionDate' => 'Transaction Date',
            'createdAt' => 'Date',
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
            'succeeded'=>array(
                'condition'=>'status = :status',
                'params'=>array(':status'=>'success'),
            ),
            'purchase'=>array(
                'condition'=>'action = :action',
                'params'=>array(':action'=>'purchase'),
            ),
        );
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
     * Subscription scope.
     * @param integer $subscriptionId Subscription id
     * @return object Transaction
     */
    public function subscriptionScope($subscriptionId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'subscriptionId = :subscription',
            'params'=>array(':subscription'=>(int)$subscriptionId),
        ));

        return $this;
    }

    /**
     * Returns transaction by it's uuid.
     * @param string $uuid Transaction UUID
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

        $criteria->with = array('user', 'user.billingInfo', 'user.salesman', 'user.accountManager', 'subscription');
        $criteria->together = true;

        if(!empty($this->status)) {
            $statuses = array();

            foreach((array)$this->status as $statusOption) {
                $statuses = array_merge($statuses, explode('|', $statusOption));
            }
            $criteria->addInCondition('status',$statuses);
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>array(
                    'createdAt'=>CSort::SORT_DESC,
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
                    'subscription.planCode'=>array(
                        'asc'=>'subscription.planCode',
                        'desc'=>'subscription.planCode DESC',
                    ),
                    'subscription.planName'=>array(
                        'asc'=>'subscription.planName',
                        'desc'=>'subscription.planName DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * @return array List of transaction statuses
     */
    public function getStatuses()
    {
        return array(
            'success'   => 'Successful Transactions',
            'void|declined' => 'Unsuccessful Transactions',
        );
    }

    /**
     * Returns subscription amount in dollars (not cents).
     * @param integer $amount Amount to be formatted. If null amountInCents attribute will be used
     * @return float Subscription amount
     */
    public function getAmount($amount = null)
    {
        if($amount === null) {
            $amount = $this->amountInCents;
        }

        return number_format($amount/100, 0);
    }

    /**
     * @return float Sum of all succeeded transactions
     */
    public function getTotalRevenue()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(amountInCents) AS total';

        $result = $this->succeeded()->purchase()->find($criteria);

        return $this->getAmount($result->total);
    }

    /**
     * Handle recurly push notification.
     * @param object $event CEvent object
     */
    public function handlePushNotification($event)
    {
        $transaction = $event->sender->transaction;

        // User object has been added in User::handlePushNotification method
        $user = $event->params['user'];

        // Subscription object has been added in Subscription::handlePushNotification method
        $subscription = $event->params['subscription'];

        $model = $this->findByUuid($transaction->uuid);

        if($model === null) {
            $model = new self;
        }

        $model->setAttributes(array(
            'userId'        => $user->id,
            'subscriptionId'=> $subscription === null ? null : $subscription->id,
            'uuid'          => (string)$transaction->uuid,
            'action'        => (string)$transaction->action,
            'currency'      => (string)$transaction->currency,
            'amountInCents' => (int)$transaction->amount_in_cents,
            'taxtInCents'   => (int)$transaction->tax_in_cents,
            'status'        => (string)$transaction->status,
            'source'        => (string)$transaction->source,
            'test'          => (int)$transaction->test,         // Test is a bool value
            'voidable'      => (int)$transaction->voidable,     // Voidable is a bool value
            'refundable'    => (int)$transaction->refundable,   // Refundable is a bool value
            'cvvResult'     => (string)$transaction->cvv_result,
            'avsResult'     => (string)$transaction->avs_result,
            'avsResultStreet' => (string)$transaction->avs_result_street,
            'avsResultPostal' => (string)$transaction->avs_result_postal,
            'transactionDate' => $transaction->created_at->format('Y-m-d H:i:s'),
        ));

        if(!$model->save()) {
            throw new CDbException('Transaction model has not been saved');
        }

        // Store subscription model as event params (for invoice).
        $event->params['transaction'] = $model;

        return true;
    }

    /**
     * Get all transactions from Recurly
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function getRecurlyTransactions()
    {
        Yii::app()->getComponent('recurly');
        return $transactions = Recurly_TransactionList::get();
    }
}
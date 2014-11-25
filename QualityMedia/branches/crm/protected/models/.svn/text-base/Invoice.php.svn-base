<?php
/**
 * This is the model class for table "invoices".
 *
 * The followings are the available columns in table 'invoices':
 * @property integer $id
 * @property integer $transactionId
 * @property string $uuid
 * @property integer $number
 * @property string $state
 * @property integer $subtotal
 * @property integer $total
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property Transactions $transaction
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Invoice extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Invoice the static model class
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
        return 'invoices';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('transactionId, uuid, number, state, subtotal, total', 'required'),
            array('transactionId', 'exist', 'className'=>'Transaction', 'attributeName'=>'id'),
            array('subtotal, total', 'numerical', 'integerOnly'=>true),
            array('uuid', 'length', 'is'=>32),
            array('state', 'length', 'max'=>20),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'transaction' => array(self::BELONGS_TO,'Transaction','transactionId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'transactionId' => 'Transaction',
            'uuid' => 'Uuid',
            'number' => 'Number',
            'state' => 'State',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Returns invoice by it's uuid.
     * @param string $uuid Invoice UUID
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

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns invoice subtotal amount in dollars (not cents).
     * @return float Invoice subtotal amount
     */
    public function getSubtotalAmount()
    {
        return number_format($this->total/100, 2);
    }

    /**
     * Returns invoice total amount in dollars (not cents).
     * @return float Invoice total amount
     */
    public function getTotalAmount()
    {
        return number_format($this->total/100, 2);
    }

    /**
     * Get invoice PDF.
     */
    public function getPdf()
    {
        return Yii::app()->getComponent('recurly')->getInvoicePdf($this->number);
    }

    /**
     * Handle recurly push notification.
     * @param object $event CEvent object
     */
    public function handlePushNotification($event)
    {
        $invoice = $event->sender->invoice;

        // User object has been added in User::handlePushNotification method
        $user = $event->params['user'];

        // Subscription object has been added in Subscription::handlePushNotification method
        $subscription = $event->params['subscription'];

        // Transaction object has been added in Transaction::handlePushNotification method
        $transaction = $event->params['transaction'];

        $model = $this->findByUuid($invoice->uuid);

        if($model === null) {
            $model = new self;
        }

        $model->setAttributes(array(
            'transactionId' => $transaction->id,
            'uuid'          => (string)$invoice->uuid,
            'number'        => (int)$invoice->invoice_number,
            'state'         => (string)$invoice->state,
            'subtotal'      => (int)$invoice->subtotal_in_cents,
            'total'         => (int)$invoice->total_in_cents,
        ));

        if(!$model->save()) {
            throw new CDbException('Invoice model has not been saved');
        }

        return true;
    }
}
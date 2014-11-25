<?php
/**
 * This is the model class for table "plans".
 *
 * The followings are the available columns in table 'plans':
 * @property integer $id
 * @property string $planCode
 * @property string $name
 * @property integer $amount
 * @property integer $setupFee
 * @property integer $intervalLength
 * @property string $intervalUnit
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Plan extends ActiveRecord
{
    const INTERVAL_DAYS = 'days';
    const INTERVAL_MONTHS = 'months';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Plan the static model class
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
        return 'plans';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('planCode, name, amount, setupFee, intervalLength, intervalUnit', 'required'),
            array('planCode', 'length', 'max'=>50),
            array('planCode', 'unique'),
            array('name', 'length', 'max'=>255),
            array('amount, setupFee, intervalLength', 'numerical', 'integerOnly'=>true),
            array('intervalUnit', 'in', 'range'=>array_keys($this->getIntervals())),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'planCode' => 'Plan Code',
            'name' => 'Name',
            'amount' => 'Amount',
            'setupFee' => 'Setup Fee',
            'intervalLength' => 'Interval Length',
            'intervalUnit' => 'Interval Unit',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('planCode',$this->planCode, true);
        $criteria->compare('name',$this->planCode, true, 'OR');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Find plan by planCode.
     * @param string $planCode Plan code
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function findByPlanCode($planCode)
    {
        return $this->findByAttributes(array('planCode'=>$planCode));
    }

    /**
     * Find plan by pricing.
     * @param string $amount Amount
     * @param string $setupFee Setup Fee
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function findByPricing($amount, $setupFee)
    {
        return $this->findByAttributes(array('amount'=>$amount * 100, 'setupFee' => $setupFee));
    }

    /**
     * Returns plan amount in dollars (not cents).
     * @return float Plan amount
     */
    public function getAmount()
    {
        return number_format($this->amount/100, 2);
    }

    /**
     * Returns plan setup fee in dollars (not cents).
     * @return float Plan setup fee
     */
    public function getSetupFee()
    {
        return number_format($this->setupFee/100, 2);
    }

    /**
     * @return array List of available intervals.
     */
    public function getIntervals()
    {
        return array(
            self::INTERVAL_DAYS => 'Days',
            self::INTERVAL_MONTHS => 'Months',
        );
    }

    /**
     * Sync plans from recurly.
     */
    public function sync()
    {
        $recurly = Yii::app()->getComponent('recurly');

        $plans = Recurly_PlanList::get();

        foreach($plans as $plan) {
            $model = $this->findByPlanCode($plan->plan_code);

            if($model === null) {
                $model = new Plan;
            }

            $model->setAttributes(array(
                'planCode'      => $plan->plan_code,
                'name'          => $plan->name,
                'amount'        => $plan->unit_amount_in_cents->getCurrency($recurly->currency)->amount_in_cents,
                'setupFee'      => $plan->setup_fee_in_cents->getCurrency($recurly->currency)->amount_in_cents,
                'intervalLength'=> $plan->plan_interval_length,
                'intervalUnit'  => $plan->plan_interval_unit,
            ));

            $model->save();
        }
    }
}
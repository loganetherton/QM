<?php
/**
 * Admin's revenue model.
 */
class Revenue extends CFormModel
{
    /**
     * From Date filter value
     * @var string
     */
    public $dateFrom;

    /**
     * To Date filter value
     * @var string
     */
    public $dateTo;

    /**
     * To Date filter value
     * @var string
     */
    public $dateRange;

    /**
     * Sort Order Direction
     * @var string
     */
    public $sortOrder = 'ASC';

    /**
     * Salesmen Ids Array
     * @var string
     */
    public $salesmenIds;

    /**
     * show Revenues cancellation data
     * @var string
     */
    public $showRevenues;

    /**
     * show Subscriptions cancellation data
     * @var string
     */
    public $showSubscriptions;

    /**
     * show Data filter
     * @var string
     */
    public $showData;

    /**
     * Stats Criteria
     * @var string
     */
    public $statsCriteria;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('dateFrom, dateTo, salesmenIds, showData, sortOrder, dateRange', 'safe'),
            array('dateFrom, sortOrder', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'dateFrom' => 'From',
            'dateTo' => 'To'
        );
    }

    /**
     * Returns total subscriptions number.
     * @param boolean $active Whether return active subscriptions only. Default to false
     * @return integer Number of subscriptions
     */
    public function getTotalSubscriptions($active = false)
    {
        $model = new Subscription;

        if($active === true) {
            $model->active();
        }

        return $model->count();
    }

    /**
     * Get total revenues value.
     * @return float Total revenues value
     */
    public function getTotalRevenues()
    {
        return Transaction::model()->getTotalRevenue();
    }

    /**
     * Returns list of 10 sales reps with most subscriptions in last X days.
     * @param integer $days Days offset
     * @return array List of sales reps
     */
    public function getTopSalesReps($days = 30)
    {
        return Salesman::model()->getTopSalesReps($days);
    }

    /**
     * Returns list of 10 sales reps with most signups in last X days.
     * @param integer $days Days offset
     * @return array List of sales reps
     */
    public function getTopSalesRepsBySignups($days = 30)
    {
        return Salesman::model()->getTopSalesRepsBySignups($days);
    }

    /**
     * @return array List of 10 salesmen with lowest attrition
     */
    public function getLowestAttrition()
    {
        return Subscription::model()->getSalesmenAttrition();
    }

    /**
     * Returns list of subscription numbers over last X months.
     * @param integer $months Months offset
     * @return array List of subscription numbers
     */
    public function getSubscriptionsPerMonth($months = 6)
    {
        return Subscription::model()->getSubscriptionsPerMonth($months);
    }


//Stats functions

    protected function getStatsCriteria()
    {
        $fields = array('salesmenIds', 'sortOrder', 'dateFrom', 'dateTo');
        $params = array();
        foreach($fields as $field) {

            if(!empty($this->{$field})) {
                array_push($params, $field);
                $params[$field] = $this->{$field};
            }
        }

        return $params;
    }

    /**
     * Returns stats by subscriptions count
     * @return array List of sales reps
     */
    public function getSaleRepsStatsBySubscriptions()
    {
        return Salesman::model()->getSaleRepsStatsBySubscriptions($this->getStatsCriteria());
    }

    /**
     * Returns stats by revenues
     * @return array List of sales reps
     */
    public function getSaleRepsStatsByRevenues()
    {
        return Salesman::model()->getSaleRepsStatsByRevenues($this->getStatsCriteria());
    }

    /**
     * Returns stats by revenues
     * @return array List of sales reps
     */
    public function getSaleRepsStatsByCancellations()
    {
        return Salesman::model()->getSaleRepsStatsByCancellations($this->getStatsCriteria());
    }

}
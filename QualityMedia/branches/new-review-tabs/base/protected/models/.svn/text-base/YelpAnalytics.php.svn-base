<?php
/**
 * Model for handling anayltical info in Yelp.
 *
 * Following properties are available
 * @property integer $id
 * @property integer $businessId
 * @property integer $yelpBusinessId
 * @property string $info
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpAnalytics extends ActiveRecord
{
    /**
     * Returns the static instance for this model
     *
     * @static
     * @access public
     * @param string $className
     * @return YelpAnalytic
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
        return 'yelp_analytics';
    }

    /**
     * Basic rules for validation
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array(
            array('businessId, yelpBusinessId, info', 'required'),
            array('businessId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('yelpBusinessId', 'exist', 'className'=>'YelpBusiness', 'attributeName'=>'id'),
            array('info', 'filter', 'filter'=>'trim'),
        );
    }

    /**
     * Defines our relations with other models
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
     * @return array Customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'businessId' => 'Business',
            'yelpBusinessId' => 'Yelp Business',
            'info' => 'Analytics Info',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Called right before validation is done
     *
     * @access protected
     * @return bool
     */
    protected function beforeValidate()
    {
        $this->info = CJSON::encode($this->info);

        return parent::beforeValidate();
    }

    /**
     * Called right after a row is found
     *
     * @access protected
     * @return void
     */
    protected function afterFind()
    {
        $this->info = CJSON::decode($this->info);

        parent::afterFind();
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
     * Calculate Visits from analytics data
     * @return int
     */
    public function getVisits($lastThirtyDays = false)
    {
        $businessProfile = Profile::model()->findByPk($this->businessId);

        if(null === $businessProfile) {
            return 0;
        }

        $totalVisits = 0;

        $businessHireDate = $businessProfile->createdAt;

        $analyticsData = $this->info;

        $OneMonthPageViews = $analyticsData['one_month']['data']['stats']['data_points']['num_page_views'];

        $nbOneMonthPageViews = count($OneMonthPageViews);

        $firstElement = $OneMonthPageViews[0];

        $monthStartDate = $firstElement[0];

        if(strtotime($businessHireDate) >= strtotime($monthStartDate) || $lastThirtyDays) {
            foreach($OneMonthPageViews as $pageView) {
                if(strtotime($pageView[0]) >= strtotime($businessHireDate)) {
                    $totalVisits += $pageView[1];
                }
            }
        }
        else {
            $OneYearPageViews = $analyticsData['one_year']['data']['stats']['data_points']['num_page_views'];

            $nbOneYearPageViews = count($OneYearPageViews);

            $firstElement = $OneYearPageViews[0];

            $yearStartDate = $firstElement[0];

            if(strtotime($businessHireDate) >= strtotime($yearStartDate)) {
                foreach($OneYearPageViews as $pageView) {
                    if(strtotime($pageView[0]) >= strtotime($businessHireDate)) {
                        $totalVisits += $pageView[1];
                    }
                }
            }
        }

        return $totalVisits;
    }

    /**
     * Get Customer Interactions count
     * @param type $lastThirtyDays
     * @return int
     */
    public function getCustomerInteractions($lastThirtyDays = false)
    {
        $businessProfile = Profile::model()->findByPk($this->businessId);

        if(null === $businessProfile) {
            return 0;
        }

        $totalInteractions = 0;

        $businessHireDate = $businessProfile->createdAt;

        $analyticsData           = $this->info;
        $OneMonthCustomerActions = $analyticsData['one_month']['data']['stats']['data_points']['num_customer_actions'];

        $nbOneMonthPageCustomerActions = count($OneMonthCustomerActions);

        $firstElement = $OneMonthCustomerActions[0];

        $monthStartDate = $firstElement[0];

        if(strtotime($businessHireDate) >= strtotime($monthStartDate) || $lastThirtyDays) {
            foreach($OneMonthCustomerActions as $customerAction) {
                if(strtotime($customerAction[0]) >= strtotime($businessHireDate)) {
                    $totalInteractions += $customerAction[1];
                }
            }
        }
        else {
            $OneYearCustomerActions = $analyticsData['one_year']['data']['stats']['data_points']['num_customer_actions'];

            $nbOneYearCustomerActions = count($OneYearCustomerActions);

            $firstElement = $OneYearCustomerActions[0];

            $yearStartDate = $firstElement[0];

            if(strtotime($businessHireDate) >= strtotime($yearStartDate)) {
                foreach($OneYearCustomerActions as $customerAction) {
                    if(strtotime($customerAction[0]) >= strtotime($businessHireDate)) {
                        $totalInteractions += $customerAction[1];
                    }
                }
            }
        }

        return $totalInteractions;
    }
}
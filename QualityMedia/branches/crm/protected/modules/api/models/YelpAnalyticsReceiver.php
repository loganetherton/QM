<?php
/**
 * Model for receiving analytics info from Yelp
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpAnalyticsReceiver extends CFormModel
{
    /**
     * @var string Stores the business ID
     */
    protected $bizId;

    /**
     * @var array The info itself
     */
    protected $info = array();

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly This param is here for compatibility purposes only.
     */
    public function setAttributes($attributes, $safeOnly = false)
    {
        $this->bizId = $attributes['business_id'];

        $this->info = $attributes['data'];
        $this->info['one_year']     = CJSON::decode($this->info['one_year']);
        $this->info['one_month']    = CJSON::decode($this->info['one_month']);
        $this->info['two_year']     = CJSON::decode($this->info['two_year']);
    }

    /**
     * Saves the information into the DB
     * @return bool
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            $yelpBusiness = YelpBusiness::model()->findByBizId($this->bizId);

            if($yelpBusiness == null) {
                throw new CException($this->bizId . ' does not exist');
            }

            $model = YelpAnalytics::model()->findByYelpBusinessId($yelpBusiness->id);
            $model = $model == null ? new YelpAnalytics : $model;

            $model->businessId      = $yelpBusiness->userId;
            $model->yelpBusinessId  = $yelpBusiness->id;
            $model->info            = $this->info;

            if(!$model->save()) {
                throw new CException('Analytics info not saved');
            }

            $transaction->commit();
        }
        catch (CException $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }

        return true;
    }
}
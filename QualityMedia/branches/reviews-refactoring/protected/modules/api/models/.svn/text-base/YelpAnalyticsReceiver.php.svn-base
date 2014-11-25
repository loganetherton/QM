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
     * Sets the internal attributes from the data received from Yelp
     *
     * @access public
     * @param array $attributes
     * @param bool $safeOnly For compatibility reasons
     * @return void
     */
    public function setAttributes($attributes, $safeOnly = false)
    {
        $this->bizId = $attributes['business_id'];

        $this->info = $attributes['data'];
        $this->info['one_year'] = CJSON::Decode($this->info['one_year']);
        $this->info['one_month'] = CJSON::Decode($this->info['one_month']);
        $this->info['two_year'] = CJSON::Decode($this->info['two_year']);
    }

    /**
     * Saves the information into the DB
     *
     * @access public
     * @return bool
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try
        {
            $profile = Profile::model()->findByYelpId($this->bizId);
            if ($profile == null)
                throw new CException($this->bizId . ' does not exist');

            $model = YelpAnalytics::model()->findByAttributes(array('businessId' => $profile->userId));
            $model = $model == null ? new YelpAnalytics : $model;

            $model->businessId = $profile->userId;
            $model->info = $this->info;

            if (!$model->save())
                throw new CException('Analytics info not saved');

            $transaction->commit();
        } catch (CException $e)
        {
            $transaction->rollback();
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

            return false;
        }

        return true;
    }
}
<?php
/**
 * Model for receiving business info from Yelp
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpInfoReceiver extends CFormModel
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

        $this->info = array_merge(YelpInfo::$info_base, $attributes['info']);
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

            $model = YelpInfo::model()->findByAttributes(array('businessId' => $profile->userId));
            $model = $model == null ? new YelpInfo : $model;

            $model->businessId = $profile->userId;
            $model->info = $this->info;
            $model->originalNodes = array_keys($this->info);

            if (!$model->save()) {
                throw new CException('Info failed to save');
            }

            $model->saveAttributes(array('saved' => YelpInfo::STATUS_SAVED));

            $transaction->commit();
        } catch (CException $e)
        {
            var_dump($e->getMessage());
            $transaction->rollback();
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

            return false;
        }

        return true;
    }
}
<?php
/**
 * Yelp businesses receiver.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpBusinessesReceiver extends CFormModel
{
    /**
     * @var object $profile Profile model.
     */
    protected $profile;

    /**
     * @var array $businesses Available businesses.
     */
    protected $businesses = array();

    /**
     * Set profile model.
     * @param object $profile Profile object
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly This param is here for compatibility purposes only.
     */
    public function setAttributes($values, $safeOnly=true)
    {
        foreach($values['biz_list'] as $business) {
            $address = str_replace(array("\r\n", "\n", "\r"), ' ', $business['address']);

            $attributes = array(
                'bizId'     => $business['id'],
                'yelpId'    => $business['yelp_id'],
                'label'     => sprintf('%s (%s)', $business['name'], $address),
            );

            $this->businesses[] = $attributes;
        }
    }

    /**
     * Save reviews.
     * @return boolean Whether all reviews have been saved
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            foreach($this->businesses as $business) {
                $model = YelpBusiness::model()->findByBizId($business['bizId']);
                $model = $model === null ? new YelpBusiness : $model;

                if(!$model->getIsNewRecord()) {
                    $business['label'] = $model->label;
                }

                $model->profileId   = $this->profile->id;
                $model->userId      = $this->profile->userId;
                $model->setAttributes($business);

                // Try to save review model
                if(!$model->save()) {
                    throw new CException(sprintf('Business (%s) has not been saved [%s]', $business['bizId'], var_export($model->getErrors(), true)));
                }
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log(sprintf('[%s] %s', $this->profile->id, $e->getMessage()), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }
}
<?php
/**
 * Model for receiving photos from Yelp
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpPhotoReceiver extends CFormModel
{
    /**
     * @var string Stores the business ID
     */
    protected $bizId;

    /**
     * @var array Stores the individual photos to populate
     */
    protected $photos = array();

    /**
     * Populates attributes for this instance
     *
     * @access public
     * @param array $attributes
     * @param bool $safeOnly Only for compatibility purposes
     * @return void
     */
    public function setAttributes($attributes, $safeOnly = false)
    {
        $this->bizId = $attributes['business_id'];

        foreach ($attributes['photos'] as $photo)
        {
            foreach ($photo['actions'] as $action => $status)
                if (!$status)
                    unset($photo['actions'][$action]);

            $photo['from_owner'] = (bool) $photo['from_owner'];

            $attribute = array(
                'photoId' => $photo['id'],
                'photoUrl' => $photo['url'],
                'fromOwner' => (int) (bool) $photo['from_owner'],
                'flagged' => 0,
                'caption' => trim($photo['caption']),
                'actions' => array_keys($photo['actions']),
                'uploaderName' => !$photo['from_owner'] ? $photo['from']['name'] : '',
                'uploaderId' => !$photo['from_owner'] ? $photo['from']['user_id'] : '',
                'uploaderProfile' => !$photo['from_owner'] ? $photo['from']['user_profile'] : '',
             );

            $this->photos[] = $attribute;
        }
    }

    /**
     * Saves the records
     *
     * @access public
     * @return bool
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try
        {
            $profile = Profile::model()->findByYelpid($this->bizId);

            if ($profile == null)
                throw new CException($this->bizId . ' does not exist');

            foreach ($this->photos as $photo)
            {
                $model = YelpPhoto::model()->findByAttributes(array('photoId' => $photo['photoId']));
                $model = $model == null ? new YelpPhoto : $model;

                $model->businessId = $profile->userId;
                $model->uploaded = 1;
                $model->setAttributes($photo);
                $model->actions = $photo['actions'];

                if (!$model->save())
                    throw new CException($photo['photoId'] . ' has not been saved');

                $model->saveAttributes(array('saved' => 1));
            }

            $transaction->commit();
        } catch (CException $e)
        {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }

        return true;
    }
}
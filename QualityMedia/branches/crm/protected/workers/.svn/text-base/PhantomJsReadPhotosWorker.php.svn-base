<?php
/**
 * PhantomJs read reviews worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadPhotosWorker extends PhantomJsWorker
{
    /**
     * Do the work.
     */
    public function doWork()
    {
        $id = $this->data['id'];
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($id);

        if($yelpBusiness === null) {
            throw new WorkerPermanentFailureException("Yelp business with provided id ({$id}) does not exist");
        }

        $companyName = $yelpBusiness->label;
        $lockKey     = 'lock-' . $yelpBusiness->id;

        $this->lockBusiness($lockKey);

        echo "Fetching photos for {$companyName} ({$yelpBusiness->id})...\n";

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_photos_read.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("Photos for {$companyName} have not been fetched (Scraper error: {$error})");
        }

        Yii::import('application.modules.api.models.YelpPhotoReceiver');

        $model = new YelpPhotoReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Photos for {$companyName} have not been saved (Database error: {$error})");
        }

        echo "Photos for {$companyName} have been saved\n";

        $this->unlockBusiness($lockKey);
    }
}
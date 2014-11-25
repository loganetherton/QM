<?php
/**
 * PhantomJs read analytics worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadAnalyticsWorker extends PhantomJsWorker
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

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_analytics_data.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("Analytics data for {$companyName} has not been fetched (Scrapper error: {$error})");
        }

        Yii::import('application.modules.api.models.YelpAnalyticsReceiver');

        $model = new YelpAnalyticsReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Analytics data for {$companyName} has not been saved (Database error: {$error})");
        }

        echo "Analytics data for {$companyName} has been saved\n";

        $this->unlockBusiness($lockKey);
    }
}
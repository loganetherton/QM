<?php
/**
 * PhantomJs read info worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadInfoWorker extends PhantomJsWorker
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

        $result = CJSON::decode($phantom->execute('yelp_info_read.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("Account info for {$companyName} has not been saved (Scraper error: {$error})");
        }

        Yii::import('application.modules.api.models.YelpInfoReceiver');

        $model = new YelpInfoReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Account info for {$companyName} has not been saved (Database error: {$error})");
        }

        echo "Account info for {$companyName} have been saved\n";

        $this->unlockBusiness($lockKey);
    }
}
<?php
/**
 * PhantomJs read reviews worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadReviewsWorker extends PhantomJsWorker
{
    /**
     * Return the number of seconds this worker needs hold a lease on the task for while it performs work.
     * @return integer Number of seconds this task needs to remain leased for
     */
    public function getRequiredLeaseTime()
    {
        return 3600;    // 60 minutes
    }

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
        $lockKey     = 'lock-'.$yelpBusiness->id;

        $this->lockBusiness($lockKey);

        echo "Fetching reviews for {$companyName} ({$yelpBusiness->id})...\n";

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_read_reviews_with_messages.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("Reviews for {$companyName} have not been fetched (Scraper error: {$error})");
        }

        Yii::import('application.modules.api.models.YelpTotalReviewReceiver');

        $model = new YelpTotalReviewReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Reviews for {$companyName} have not been saved (Database error: {$error})");
        }

        echo "Reviews for {$companyName} have been saved\n";

        $this->unlockBusiness($lockKey);
    }
}
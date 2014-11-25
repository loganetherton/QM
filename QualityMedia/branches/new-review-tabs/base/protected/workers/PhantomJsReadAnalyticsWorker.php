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

        $result = $this->getResult($phantom, 'yelp_analytics_data.js');

        $this->saveCurrentData($result);
        $this->saveHistoricalData($result, $yelpBusiness);

        echo "Analytics data for {$companyName} has been saved\n";

        $this->unlockBusiness($lockKey);
    }

    /**
     * Save current data in primary database.
     * @param array $result Scraper result
     * @return boolean True on success
     * @throws CException on save failure
     */
    protected function saveCurrentData($result)
    {
        Yii::import('application.modules.api.models.YelpAnalyticsReceiver');

        $model = new YelpAnalyticsReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Analytics data for {$companyName} has not been saved (Database error: {$error})");
        }

        return true;
    }

    /**
     * Save historical data in NoSQL database.
     * @param array $result Scraper result
     * @param object $yelpBusiness YelpBusiness object
     * @return boolean True on success
     * @throws CException on save failure
     */
    protected function saveHistoricalData($result, $yelpBusiness)
    {
        $id = (int)$this->data['id'];

        $model = YelpAnalyticsStorage::model();

        $model->setCollectionName($id);
        $dateFormatted = $model->formatDate('today');

        // Check if have a record for today
        $document = $model->findOne(array(
            'businessId' => $id,
            'date'       => $dateFormatted,
        ));

        if($document) {
            // If we found a document, update the collection name for to update the current document
            $model = $document;
        }
        else {
            // If we don't find a record, create a new instance
            $model = new YelpAnalyticsStorage;

            $model->setCollectionName($id);
            $model->businessId     = $id;
            $model->yelpBusinessId = $yelpBusiness->bizId;
        }

        // Create an index for this client, if one is not already created
        $model->createIndexFile($id);

        // Set the remaining attributes for this document
        $model->date = $dateFormatted;
        $model->setAttributes($result);

        if(!$model->save()) {
            throw new CException("Analytics data for {$yelpBusiness->label} has not been saved (MongoDB error)");
        }

        return true;
    }
}
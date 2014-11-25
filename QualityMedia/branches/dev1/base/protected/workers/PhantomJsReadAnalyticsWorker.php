<?php
/**
 * PhantomJs read analytics worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadAnalyticsWorker extends PhantomJsWorker
{
    /**
     * @return array Script-specific params
     */
    protected function getScriptParams()
    {
        return array();     // No additional params are required
    }

    /**
     * @return string Script name
     */
    protected function getScriptName()
    {
        return 'yelp_analytics_data.js';
    }

    /**
     * Process PhantomJs script result.
     * @param mixed $result Script execution result
     */
    protected function processPhantomJsResult($result)
    {
        $companyName = $this->getYelpBusinessModel()->label;

        $this->saveCurrentData($result);
        $this->saveHistoricalData($result, $this->getYelpBusinessModel());

        echo "Analytics data for {$companyName} has been saved\n";
    }

    /**
     * Save current data in primary database.
     * @param array $result Scraper result
     * @return boolean True on success
     * @throws CException on save failure
     */
    protected function saveCurrentData($result)
    {
        $companyName = $this->getYelpBusinessModel()->label;

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

        // Set the collection name to correspond to this client's businessId
        $model->setCollectionName($id);

        // Format today's date in the Yelp format
        $dateFormatted = new DateTime('yesterday');
        $dateFormatted = (int)$dateFormatted->format('Ymd');

        // Check if have a record for today
        $document = $model->findOne(array(
            'businessId' => $id,
            'date'       => $dateFormatted,
        ));

        if(isset($document)) {
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

        // Retrieve all documents for this collection from within the past month
        $dateEnd          = new DateTime('yesterday');
        $dateEndFormatted = (int)$dateEnd->format('Ymd');

        $dateStart          = new DateTime('-30 days');
        $dateStartFormatted = (int)$dateStart->format('Ymd');

        $docs = YelpAnalyticsStorage::model()->find(array('date' => array('$gte' => $dateStartFormatted)));

        // If we don't have a full month worth of documents, find out which days are missing
        // EMongoDoc apparently implements iterable but not traversable, so I need to iterate to get the most recent doc
        $docCollection = array();
        if(count($docs) === 0) {
            $lastDoc = 'none';
        }
        elseif(count($docs) < 30) {
            foreach($docs as $doc) {
                $docCollection[] = $doc->date;
                // There's no reason to fill anything in before the most recently saved document, since that will
                // have filled in previously missed dates
                if(!isset($lastDoc)) {
                    $lastDoc = $doc;
                }
            }
        }

        $oneDayInterval = !empty($lastDoc) ? new DateInterval('P1D') : null;
        $currentDate = null;
        if($oneDayInterval) {
            // Determine for which dates we do not have documents
            while($currentDate != $dateEndFormatted) {
                $currentDate = (int)$dateStart->format('Ymd');
                if(!in_array($currentDate, $docCollection)) {
                    $missingDocDates[] = $currentDate;
                }

                $dateStart->add($oneDayInterval);
            }

            // Make sure not to set the current date like the others, so we can preserve monthly values
            if(in_array($currentDate, $missingDocDates)) {
                $missingDocDates = array_flip($missingDocDates);
                unset($missingDocDates[$currentDate]);
                $missingDocDates = array_flip($missingDocDates);
            }

            // Fill in the missing data
            $replacementDoc = null;
            if(!empty($missingDocDates)) {
                foreach($missingDocDates as $missingDoc) {
                    $replacementDoc = new YelpAnalyticsStorage();
                    $replacementDoc->date = $missingDoc;
                    $replacementDoc->setCollectionName($id);
                    $replacementDoc->businessId = $id;
                    $replacementDoc->yelpBusinessId = $yelpBusiness->bizId;
                    $replacementDoc->setAttributesMissingDoc($result);

                    $replacementDoc->save();
                }
            }
        }

        // Save the most recent document after all the blanks have been filled in
        if(!$model->save()) {
            throw new CException("Analytics data for {$yelpBusiness->label} has not been saved (MongoDB error)");
        }

        return true;
    }
}
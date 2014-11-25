<?php
/**
 * Controller for handling email report creation, sending, and saved reports
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */

class EmailReportController extends AmController
{
    public $layout = 'column2';
    /**
     * Create the email to be sent
     *
     * @access public
     * @param int $id
     * @param int $startDate Start date, formatted: YYYYMMDD
     * @param int $endDate End date, formatted: YYYYMMDD
     * @param int $redirect Used to prevent endless redirect loops when data cannot be found
     */
    public function actionCreate($id = null, $startDate = null, $endDate = null, $redirect = 0)
    {
//        /**
//         * Messing with the email report storage mechanism
//         */
//
//        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($id);
//
//        $phantom = Yii::app()->getComponent('phantomjs');
//
//        $phantom->setParams(array(
//            $yelpBusiness->profile->yelpUsername,
//            $yelpBusiness->profile->yelpPassword,
//            $yelpBusiness->bizId,
//        ));
//
////        $phantomResult = $phantom->execute('yelp_analytics_data.js');
//        $phantomResult = file_get_contents('/home/logan/Documents/QualityMedia/Projects/EmailReports/sample_data_021014/client_1.json');
////        file_put_contents('/home/logan/Documents/QualityMedia/Projects/EmailReports/sample_data_021014/client_1.json', $phantomResult);
//        $result = CJSON::decode($phantomResult);
////        dd($result);
//        if(isset($result['error'])) {
//            echo $result['error'];
//
//            return;
//        }
//
//        /**
//            Here's what needs to happen:
//
//            1) Get the data for today
//            2) Save only the data for this day
//            3) Check to see which documents we are missing within the past 30 days
//            4) Fill in the blanks
//         */
//
//        $model = YelpAnalyticsStorage::model();
//        // Set the collection name to correspond to this client's businessId
//        $model->setCollectionName($id);
//
//        // Format today's date in the Yelp format
//        //$dateFormatted = $model->formatDate('yesterday');
//        $dateFormatted = new DateTime('yesterday');
//        $dateFormatted = (int)$dateFormatted->format('Ymd');
//
//        // Check if have a record for today
//        $document = $model->findOne(array(
//                                        'businessId' => (int)$id,
//                                        'date' => $dateFormatted,
//                                        ));
////        d($document);
//        // If we found a document, update the collection name for to update the current document
//        if (isset($document)) {
//            $model = $document;
//        // If we don't find a record, create a new instance
//        } else {
//            $model = new YelpAnalyticsStorage;
//            // Set the collection info based on the current client
//            $model->setCollectionName($id);
//            $model->businessId = (int)$id;
//            $model->yelpBusinessId = $yelpBusiness->bizId;
//        }
//        // Create an index for this client, if one is not already created
//        $model->createIndexFile($id);
//
//        // Set the remaining attributes for this document
//        $model->date = $dateFormatted;
//        $model->setAttributes($result);
//
//        // Need to save at the end so that this isn't included in the list of returned results
//
//        // Retrieve all documents for this collection from within the past month
//        $dateEnd = new DateTime('yesterday');
//        //$dateEnd = new DateTime('today');
//        $dateEndFormatted = (int)$dateEnd->format('Ymd');
//        $dateStart = new DateTime('-30 days');
//        $dateStartFormatted = (int)$dateStart->format('Ymd');
////        d($dateStartFormatted);
////        dd($dateEndFormatted);
//
//        //$criteria = new EMongoCriteria;
//        //$criteria->setSort(array('date' => -1));
//        ////$criteria->addCondition(array('date' => array('$gte' => $dateStartFormatted)));
//        ////$cursor = $mongo->$db->$collection->find(array('date_of_download' => array('$in' => array('2012-09-11', '2012-09-12'))));
//        //$dateRangeCriteria = $criteria->condition;
//        //$dateRangeCriteria['date']['$gte'] = $dateStartFormatted;
//        //$dateRangeCriteria['date']['$lte'] = $dateEndFormatted;
//        //$criteria->setCondition($dateRangeCriteria);
//        //dd($criteria);
//        // Get all of the documents for the past 30 days
//        //$docs = YelpAnalyticsStorage::model()->find($criteria);
//        //dd(count($docs));
//
//        $docs = YelpAnalyticsStorage::model()->find(array('date' => array('$gte' => $dateStartFormatted)));
////        d($docs);
////        dd(count($docs));
//        // If we don't have a full month worth of documents, find out which days are missing
//        // EMongoDoc apparently implements iterable but not traversable, so I need to iterate to get the most recent doc
//        $docCollection = array();
//        if (count($docs) === 0) {
//            $lastDoc = 'none';
//        } elseif (count($docs) < 30) {
//            foreach ($docs as $doc) {
////                dd($doc);
//                $docCollection[] = $doc->date;
//                // There's no reason to fill anything in before the most recently saved document, since that will
//                // have filled in previously missed dates
//                if (!isset($lastDoc)) {
//                    $lastDoc = $doc;
//                }
//            }
//        }
////        d($doc);
////        d($docCollection);
////        dd($lastDoc);
//        $oneDayInterval = !empty($lastDoc) ? new DateInterval('P1D') : null;
//        $currentDate = null;
////d($oneDayInterval);
//        if ($oneDayInterval) {
//            // Determine for which dates we do not have documents
//            while ($currentDate != $dateEndFormatted) {
//                $currentDate = (int)$dateStart->format('Ymd');
//                //            dd($currentDate);
//                if (!in_array($currentDate, $docCollection)) {
//                    $missingDocDates[] = $currentDate;
//                }
//                $dateStart->add($oneDayInterval);
//            }
////            d($missingDocDates);
//
//            // Make sure not to set the current date like the others, so we can preserve monthly values
//            if (in_array($currentDate, $missingDocDates)) {
//                $missingDocDates = array_flip($missingDocDates);
//                unset($missingDocDates[$currentDate]);
//                $missingDocDates = array_flip($missingDocDates);
//            }
////dd($missingDocDates);
//            $replacementDoc = null;
//            //        Fill in the missing data
//            if (!empty($missingDocDates)) {
//                foreach ($missingDocDates as $missingDoc) {
//                    $replacementDoc = new YelpAnalyticsStorage();
//                    $replacementDoc->date = $missingDoc;
//                    $replacementDoc->setCollectionName($id);
//                    $replacementDoc->businessId = (int)$id;
//                    $replacementDoc->yelpBusinessId = $yelpBusiness->bizId;
//                    $replacementDoc->setAttributesMissingDoc($result);
//                    $replacementDoc->save();
//                }
//            }
//        }
////dd($model);
//        // Save the most recent document after all the blanks have been filled in
//        $model->save();
//dd($model);
        /**
         * End messing with the email report storage mechanism
         */

        try {
            // Create a new email report
            $model = new EmailReport();

            // Typecast since MongoDB doesn't
            $id = empty($id) ? $model->findFirstClientThisAm() : (int)$id;

            // Make sure this client exists in the MySQL database
            $yelpBusiness = YelpBusiness::model()->find('id = :id', array(':id' => $id));
            if (!$yelpBusiness) {
                throw new CException('The requested ID must correspond to a Yelp client.');
            }

            // Get the user information for email address
            $client = User::Model()->with('accountManager')->find('t.id = :id', array(':id' => $yelpBusiness->attributes['userId']));

            // Make sure this client is associated with this account manager
            if ($client->accountManagerId != Yii::app()->user->getId()) {
                throw new CException('You can only view clients for whom you are the account manager.');
            }
            // Create the list of clients for the dropdown menu
            $clientsForDropdown = $model->getClientsForDropdown($client);
            // Get the YelpAnalyticsStorage instance, change collection to this client
            $yelpAnalyticsStorage = YelpAnalyticsStorage::model();
            $yelpAnalyticsStorage->setCollectionName($id);
            // Get today's date
            $today = $yelpAnalyticsStorage->formatDate('today');

            // Set the end date to today and the start day to 1 month ago by default
            $startDate = $yelpAnalyticsStorage->formatDate(empty($startDate) ? '-1 month' : $startDate);
            $endDate = $yelpAnalyticsStorage->formatDate(empty($endDate) ? 'today' : $endDate, true);

            // Make sure we only serve up nearly complete months
            $startDateMonthChecker = new DateTime($startDate);
            $startDateMonthChecker->modify('first day of this month');
            $startDateMonthChecker->add(new DateInterval('P5D'));

            if (!($startDate < (int)$startDateMonthChecker->format('Ymd'))) {
                $startDate = new DateTime($endDate);
                $startDate->modify('first day of this month');
                $startDate = (int)$startDate->format('Ymd');
            }

            // Get the range for which historical data is available
            $docDateRange = $yelpAnalyticsStorage->verifyDateRange($id, $startDate, $endDate);

            // Handle redirection for when we can't find the requested data
            $redirectInstructions = $model->handleRedirection($id, $docDateRange, $redirect, $startDate, $endDate);

            if (!empty($redirectInstructions)) {
                $this->redirect($redirectInstructions);
            }
            // Get client photo
            $photo = $model->getPhoto($id);

            // Make sure we're not doing this for both creating the report and sending it
            if (!isset($_POST['EmailReport'])) {
                // Get the data for the requested date range
                $combinedData = $model->handleS3($yelpAnalyticsStorage, $id, $today, $startDate, $endDate);
            // Send the email
            } else {
//                d($_POST);
                // Upload any attached image to S3
//                $imageType = getimagesize($_FILES['EmailReport']['tmp_name']['image_attachment']);
//                $extension = explode('/', $imageType['mime']);
//                $attachmentName = md5(uniqid('', true)) . '.' . $extension[1];
//                d($attachmentName);
//                dd($_FILES['EmailReport']);
                if (isset($_FILES['EmailReport'])) {
                    $attachment = CUploadedFile::getInstance($model, 'image_attachment');
                    //                d($attachment);
//                    $s3Component = Yii::app()->s3Component;
//                    $bucket = 'qm-static';
//                    $uri = "emailReport/images/attachments/$id/{$dateStartFormatted}{$dateEndFormatted}/{$attachment->name}";
//                    //                dd($uri);
//                    if (!$s3Component->putObject($s3Component->inputFile($attachment->tempName), $bucket, $uri, S3::ACL_PUBLIC_READ)) {
//                        throw new CException('Could not upload attachment: ' . $attachment->name);
//                    }
//                    $model->image_attachment = $uri;

                    $emailReportId = $model->sendNewEmailReport($photo, $id, $startDate, $endDate, $attachment);
                } else {
                    $emailReportId = $model->sendNewEmailReport($photo, $id, $startDate, $endDate);
                }

                dd($model);
                // Redirect to the newly created email report or let the user know of a failure
                $emailReportId ? $this->redirect(array('emailReport/saved/emailReportId/' . $emailReportId . '/created/true'))
                    : Yii::app()->getUser()->setFlash('failure', 'Unable to send email. Please contact the devs.');
            }

            $this->render('create', array(
                'id' => $id,
                'yelpBusiness' => $yelpBusiness,
                'clientsForDropdown' => $clientsForDropdown,
                'model' => $model,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'data' => $combinedData,
                'availableDateRange' => $docDateRange,
                'photo' => $photo,
                'client' => $client,
                'today' => $today,
            ));
        } catch (CException $e) {
            $this->render('create', array(
                'id' => $id,
                'yelpBusiness' => !empty($yelpBusiness) ? $yelpBusiness : null,
                'clientsForDropdown' => !empty($clientsForDropdown) ? $clientsForDropdown : null,
                'model' => $model,
                'errorMessage' => $e->getMessage(),
            ));
        }
    }

    /**
     * Get saved emails for this account manager
     *
     * @param int $emailReportId ID of requested email report
     * @param bool $created Whether the report was just created
     * @return array $emails
     */
    public function actionSaved($emailReportId = null, $created = false)
    {
        // Also get the current user, in case it's not the account manager that created it
        $accountManager = AccountManager::model()->find('id = :id' , array(':id' => Yii::app()->user->id));

        // Get the current email report, if one is selected
        $model = (isset($emailReportId) && (int)$emailReportId)
            ? EmailReport::model()->with('yelpBusiness', 'accountManager')->find('t.id = :id', array(':id' => $emailReportId))
            : new EmailReport;

        // Display a flash if the email was just created
        $model->setFlashSavedEmailReport($created, $accountManager);
        // Get the saved email reports for this account manager
        $savedReports = $model->getSavedReports();

        // Send the email
        if(isset($_POST['EmailReport'])) {
            $model->sendSavedEmailReport();
        }

        // Render the view  
        $this->render('saved', array(
            'accountManager' => $accountManager,
            'model' => $model,
            'savedReports' => $savedReports,
            'emailReportId' => isset($emailReportId) ? $emailReportId : null,
            'created' => $created ? $created : null,
        ));
    }
}
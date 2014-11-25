<?php
/**
 * Controller for handling email report creation, sending, and saved reports
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */

class EmailReportController extends AmController
{
    public $layout = 'column2';

    public $jrViewId = null;

    /**
     * [beforeAction description]
     */
    public function beforeAction($action)
    {

        //Handling junior view mode
        $this->jrViewId = Yii::app()->getRequest()->getParam('jr');

        if($this->jrViewId) {
            $accountManager = $this->loadModel($this->jrViewId, 'AccountManager');
            $this->setJrMode($accountManager);
        }

        return parent::beforeAction($action);
    }

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
        try {
            // Create a new email report
            $model = new EmailReport();

            //Handling junior view mode
            $juniorViewId = Yii::app()->getRequest()->getParam('jr');

            // Typecast since MongoDB doesn't
            $id = empty($id) ? $model->findFirstClientThisAm() : (int)$id;

            // Make sure this client exists in the MySQL database
            $yelpBusiness = YelpBusiness::model()->find('id = :id', array(':id' => $id));
            if (!$yelpBusiness) {
                throw new CException('The requested ID must # code...correspond to a Yelp client.');
            }
            // See if the status is enabled
            if (!$yelpBusiness->status) {
                throw new CException('The selected client is not active.');
            }

            // Get the user information for email address
            $client = User::Model()->with('accountManager')->find('t.id = :id', array(':id' => $yelpBusiness->attributes['userId']));

            // Make sure this client is associated with this account manager or his junior
            $userId = Yii::app()->user->getId();

            if (!in_array($userId, array($client->accountManagerId, $client->accountManager->seniorManagerId)) && !Yii::app()->user->isSuperSenior()) {
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

            // Get the range for which historical data is available
            $docDateRange = $yelpAnalyticsStorage->verifyDateRange($startDate, $endDate);

            // Handle redirection for when we can't find the requested data
            $redirectInstructions = $model->handleRedirection($id, $docDateRange, $redirect, $startDate, $endDate, $juniorViewId);

            if (!empty($redirectInstructions)) {
                $this->redirect($redirectInstructions);
            }

            // Make sure we have data to serve up nearly complete months
            $startDateMonthChecker = new DateTime($docDateRange['firstDoc']);
            $startDateMonthChecker->modify('first day of this month');
            $startDateMonthChecker->add(new DateInterval('P5D'));

            // If the start date is gte than the first date for which data is available
            // change the start date to the beginning of the month for which the data end
            if ($startDate >= (int)$startDateMonthChecker->format('Ymd')) {
                $startDate = new DateTime($endDate);
//                $startDate->modify('first day of this month');
                $startDate->modify('-1 month');
                $startDate = (int)$startDate->format('Ymd');
                $oneMonth = true;
            }

            // Get client photo
            $photo = $model->getPhoto($id);
            // Make sure we're not doing this for both creating the report and sending it
            if (!isset($_POST['EmailReport'])) {
                // Get the data for the requested date range
                $combinedData = $model->handleS3($yelpAnalyticsStorage, $id, $today, $startDate, $endDate);
            // Send the email
            } else {
                if (!empty($_FILES['EmailReport']['size']['image_attachment'])) {
                    $attachment = CUploadedFile::getInstance($model, 'image_attachment');
                    $attachmentName = str_replace(' ', '', $attachment->name);
                    // Upload any attached image to S3 to save in the database
                    $s3Component = Yii::app()->s3Component;
                    $bucket = 'qm-static-new';
                    $uri = "emailReport/images/attachments/$id/{$startDate}{$endDate}/$attachmentName";
                    if (!$s3Component->putObject($s3Component->inputFile($attachment->tempName), $bucket, $uri, S3::ACL_PUBLIC_READ)) {
                        throw new CException('Could not upload attachment: ' . $attachmentName);
                    }
                    $emailReportId = $model->sendNewEmailReport($photo, $id, $startDate, $endDate, $attachment, $this->resourceUrl($uri, 's3'));
                } else {
                    $emailReportId = $model->sendNewEmailReport($photo, $id, $startDate, $endDate);
                }
                // Redirect to the newly created email report or let the user know of a failure
                $redirectUrl = 'emailReport/saved/emailReportId/' . $emailReportId . '/created/true';
                if($juniorViewId) {
                    $redirectUrl .= "/jr/{$juniorViewId}";
                }

                $emailReportId ? $this->redirect(array($redirectUrl))
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
                'oneMonth' => isset($oneMonth) ? $oneMonth: null,
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
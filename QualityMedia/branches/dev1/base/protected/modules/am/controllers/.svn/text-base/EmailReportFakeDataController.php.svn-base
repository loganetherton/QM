<?php
/**
 * Controller for handling email report creation, sending, and saved reports
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */

class EmailReportFakeDataController extends AmController
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
        // Create a new email report    
        $model = new EmailReportFakeData();
        
        $photo = null;
        $yelpBusiness = YelpBusiness::model()->find('id = :id', array(':id' => 1));
        $client = User::Model()->with('accountManager')->find('t.id = :id', array(':id' => $yelpBusiness->attributes['userId']));
        $clientsForDropdown = $model->getClientsForDropdown($client);
        if (isset($_POST['EmailReportFakeData'])) {
            // Format the email body content for sending
            $emailReportId = $model->sendNewEmailReport($photo, 1, 20140131);
            // Redirect to the newly created email report or let the user know of a failure
            $emailReportId ? $this->redirect(array('emailReportFakeData/saved/emailReportId/' . $emailReportId . '/created/true'))
                : Yii::app()->getUser()->setFlash('failure', 'Unable to send email. Please contact the devs.');
        } else {
            $combinedData = CJSON::decode(file_get_contents(Yii::app()->getComponent('s3Resource')->getResource("emailReport/fakeJson.json", 's3')));
            $this->render('create', array(
                'id' => 1,
                'yelpBusiness' => $yelpBusiness,
                'clientsForDropdown' => $clientsForDropdown,
                'model' => $model,
                'startDate' => 20131201,
                'endDate' => 20140131,
                'data' => $combinedData,
                'availableDateRange' => array('firstDoc' => 20131201, 'lastDoc' => 20140131),
                'photo' => $photo,
                'client' => $client,
                'today' => 20140131,
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
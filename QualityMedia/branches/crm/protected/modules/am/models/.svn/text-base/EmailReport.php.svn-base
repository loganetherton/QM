<?php
/**
 * EmailReport model for saving email reports to the database
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
class EmailReport extends ActiveRecord
{
    /**
     * Table columns in DB, mostly corresponding to form items
     *
     * public $id Email report ID
     * public $accountManagerId Account Manager ID
     * public $toAddress Address to which the email report is sent
     * public $fromAddress Address from which the email report is shown as having been sent
     * public $subject Email subject
     * public $textOrGraph Whether text or graph version of email report is sent/saved
     * public $emailContentText The email content for the text version of the email report
     * public $emailContentGraph The email content for the graph version of the email report
     * public $dateRange Date range of data
     * public $yelpBusinessId Yelp business Id
     * public $createAt Timestamp for email report creation
     * public $updatedAt Timestamp for updating sent email report
     */
    
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    public function tableName()
    {
        return 'email_reports';
    }

    /**
     * Validation rules for saving the model
     */
    public function rules()
    {
        return array(
            // Require to address, from address, subject line, and email content
            array('toAddress, fromAddress, subject, emailContentText, emailContentGraph', 'required', 'message' => '{attribute} is required.'),
            // Validate the email addresses are good, and only a single address
            array('toAddress', 'email', 'message' => 'Enter a valid email address.'),
            array('toAddress, fromAddress', 'filter', 'filter' => 'trim'),
            array('subject', 'filter', 'filter' => 'trim'),
            array('dateRange', 'match', 'pattern' => '/\d{16}/'),
        );
    }
    
    /**
     * Declares any relations with other models
     *
     * @todo Add in the other side of the relationship on the other models
     */
    public function relations()
    {
        return array(
            // Each report belongs to an account manager, a Yelp business, an a Yelp analytics record
            'accountManager' => array(self::BELONGS_TO, 'AccountManager', 'accountManagerId'),
            'yelpBusiness' => array(self::BELONGS_TO, 'YelpBusiness', 'yelpBusinessId'),
        );
    }
    
    /**
     * Allow authenticated users to send and view saved email reports
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // Allow all authenticated AMs to create, send, and get sent reports
                'actions' => array('create, saved'),
                'users' => array('@'),
            ),
            array(
                'deny', // Deny everything else
                'users'=>array('*'),
            ),
        );
    }
    
    /**
     * Attribute labels, primarily for error handling here
     */
    public function attributeLabels()
    {
        return array(
            'toAddress' => 'To address',
            'fromAddress' => 'From address',
            'subject' => 'Subject line',
            'dateStart' => 'Choose a start date',
            'dateEnd' => 'Choose an end date',
        );
    }
    
    /**
     * Define a scope to only display the ten most recently updated reports for the saved report drop down list
     *
     * @return array Scopes
     */
    public function scopes() {
        return array(
            'recent' => array(
                'order' => 'updatedAt DESC',
                'limit' => 10,
            )
        );
    }
    
    /**
     * Set timestamps for creating new email reports, and modifying existing ones
     *
     * @return bool
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->createdAt = new CDbExpression('NOW()');
            $this->updatedAt = new CDbExpression('NOW()');
        } else {
            $this->updatedAt = new CDbExpression('NOW()');
        }
     
        return parent::beforeSave();
    }
    
    /**
     * Determine the first client for this AM, if none is specified
     *
     * @return int Client Id
     */
    public function findFirstClientThisAm()
    {
        $clientsForThisAm = User::Model()->with('accountManager')->findAll();
        $firstClient = array_shift($clientsForThisAm);
        return (int)$firstClient->id;
    }
    
    /**
     * Get client photo for inclusion in reports
     *
     * @param int $id Yelp business ID
     * @return Photo Photo for this client
     */
    public function getPhoto($id)
    {
        $photosModel = Photo::model()->yelpBusinessScope($id)->notDeleted();
        $photoDataProvider = $photosModel->search();
        $photo = $photoDataProvider->getData();
        $photo = empty($photo) ? null : $photoDataProvider->getData();
        return $photo[0];
    }
    
    /**
     * Redirect to the first and last valid dates if a date was outside when data is available
     * @todo Need to put a notification here that the requested date isn't available
     *
     * @param int $id Yelp Business ID
     * @param array $docDateRange Range for which documents are available
     * @param int $redirectNumber Number of previous redirect attempts (to prevent an infinite loop)
     * @param int $startDate Start date for getting data
     * @param int $endDate End date for getting data
     * @return string Redirect URL
     */
    public function handleRedirection($id, $docDateRange, $redirectNumber, $startDate, $endDate)
    {
        if (isset($docDateRange['dateRangeStartInvalid'])) {
            if ($redirectNumber > 2) {
                // If a new start date and end date have been tried, throw an exception
                throw new CException('There is no valid data available for this client');
            }
            // Redirect to the first good start date
            return Yii::app()->createUrl('am/emailReport/create', array('id' => $id,
                                                                        'startDate' => $docDateRange['firstDoc'],
                                                                        'redirect' => ++$redirectNumber));
        }
        
        if (isset($docDateRange['dateRangeEndInvalid'])) {
            if ($redirectNumber > 2) {
                // If a new start date and end date have been tried, throw an exception
                throw new CException('There is no valid data available for this client');
            }
            // See if we already have a good start date
            if (strpos($_SERVER['REQUEST_URI'], 'startDate')) {
                preg_match('/startDate\/(\d{8})/', $_SERVER['REQUEST_URI'], $startDateFromUri);
            }
            
            // Redirect to the first good end date
            return Yii::app()->createUrl('am/emailReport/create', array('id' => $id,
                                                                        'startDate' => isset($startDateFromUri[1]) ? $startDateFromUri[1] : null,
                                                                        'endDate' => $docDateRange['lastDoc'],
                                                                        'redirect' => ++$redirectNumber));
        }
        
        // Alert the user
        if ($redirectNumber) {
            Yii::app()->getUser()->setFlash('success', 'The requested date range was unavailable. The closest available data is displayed.');
        }
    }
    
    /**
     * Gets clients to populate the dropdown menu of other clients for which the AM can create reports
     *
     * @param User $client Client
     * @return array Clients for dropdown
     */
    public function getClientsForDropdown($client)
    {
        // This line is only here because of a lack of planning.
        $client = $client ?: User::Model()->with('accountManager')->find();
        // Create the client drop down based on the clients available to this AM
        if ($client->accountManager->showOnlyLinkedFeeds == 1) {
            $yelpClients = Client::model()->findAll('accountManagerId = ' . Yii::app()->user->id);
            foreach ($yelpClients as $yelpClient) {
                $clientsForDropdown[] = YelpBusiness::model()->findAll('userId = ' . $yelpClient->id);
            }
        } else {
            $clientsForDropdown = YelpBusiness::model()->findAll();
        }
        if (empty($clientsForDropdown)) {
            throw new CException('No clients are available for this account manager');
        }
        return $clientsForDropdown;
    }
    
    /**
     * @param string $emailContent Email body
     * @param Photo $photo Client photo
     * @param array $graphs Graphs requested for this email report
     * @param int $id Client ID
     * @param int $today Today's date. Format: YYYYMMDD
     * @return string Email Content, HTML formatted
     */
    public function formatEmailContent($emailContent, $photo = null, $graphs = null, $id= null, $startDate = null, $endDate = null)
    {
        // Link client photo from Yelp
        if (!empty($photo)) {
            $emailContent = preg_replace('/<img.*Client\sphoto.*\">/i', '<img src="' . $photo->photoUrl . '">', $emailContent);
        }
        // Link calendar from S3
        $emailContent = preg_replace('/<img.*calendar.*\">/i', '<img src="'
            . Yii::app()->getComponent('s3Resource')->getResource("emailReport/images/calendar.png", 's3') . '">', $emailContent);
        // Link arrow-up.png from S3
        $emailContent = preg_replace('/<img.*arrow-up.*\">/i', '<img src="'
            . Yii::app()->getComponent('s3Resource')->getResource("emailReport/images/arrow-up.png", 's3') . '">', $emailContent);
        // Link arrow-down.png from S3
        $emailContent = preg_replace('/<img.*arrow-down.*\">/i', '<img src="'
            . Yii::app()->getComponent('s3Resource')->getResource("emailReport/images/arrow-down.png", 's3') . '">', $emailContent);
        // Remove the textarea used by WYSIHTML5
        $emailContent = preg_replace('/\<[\/]?textarea.*/', '', $emailContent);
        // Alter <img src...> tags to reference the graphs linked from S3
        if (isset($graphs) && !empty($graphs)) {
            foreach ($graphs as $replacement) {
                $replacement = substr($replacement, 6);
                // Width is set for 1000px, setting max-width to preserve formatting for small screens
                $emailContent = preg_replace('/<img\sstyle.*' . $replacement . '.*\"\>/',
                    '<img src="' . Yii::app()->getComponent('s3Resource')->
                    getResource("emailReport/images/clientCharts/$id/{$startDate}{$endDate}/$replacement.png", 's3')
                                             . '" style="max-width: 100%">', $emailContent);
            }
        }
        // Return the email content without all of the HTML encoding
        return html_entity_decode($emailContent);
    }
    
    /**
     * Determine which graphs were included in the emai;
     *
     * @param string $emailContent Content of email
     * @return array Graphs
     */
    public function getRequestedGraphs($emailContent)
    {
        preg_match_all('/alt="(graph-.*)" /', $emailContent, $matches);
        return $matches[1];
    }
    
    /**
     * Determines which values associated with the data is for which requested month
     *
     * @param array $data Input data
     * @param int $month Requested month
     * @return array $result Data for the requested month
     */
    public function findValuesAssociatedWithMonth($data, $month)
    {
        $result = array();
        foreach($data as $k => $v){
            if (substr($k, 0, 6) == substr($month, 0, 6)) {
                $result[] = $v;
            }
        }
        return $result;
    }
    
    /**
     * Create an instance of YiiMailer and set the standard parameters
     *
     * @param EmailReport $model
     * @return YiiMailer
     */
    public function setMailParameters()
    {
        $mail = new YiiMailer();
        $mail->isSmtp();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Host = 'smtp.gmail.com';
        //$mail->SMTPDebug  = 2; // For debugging
        $mail->SMTPDebug  = false;
        $mail->Username = 'no-reply@qualitymedia.com';
        $mail->Password = 'media4321';
        $mail->setFrom($this->attributes['fromAddress'], $this->attributes['fromAddress']);
        $mail->setTo($this->attributes['toAddress']);
        $mail->setSubject($this->attributes['subject']);
        return $mail;
    }
    
    /**
     * Helper function to format the client array for dropdown
     *
     * @return array $yelpBusinesses Flattened array of Yelp businesses.
     */
    public static function formatClientsArrayForDropdown(&$yelpBusinesses)
    {
        foreach ($yelpBusinesses as $clientsKey => $clientArray) {
            if (count($clientArray) > 1) {
                foreach ($clientArray as $clientKey => $client) {
                    $yelpBusinesses[] = array($client);
                    unset($yelpBusinesses[$clientsKey][$clientKey]);
                }
            }
        }
        // Get rid of the empty array elements
        return array_filter($yelpBusinesses);
    }
    
    /**
     * Empty and delete the newly created temporary image directory
     *
     * @param string $dir Directory holding the client charts locally
     * @return bool Success
     */
    public static function deleteLocalCharts($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    } 
    
    /**
     * Create charts locally and upload them to S3
     *
     * @param S3Component $s3Component
     * @param string $bucket QM bucket name
     * @param string $json JSON from YelpAnalyticsStorage data for this client
     * @param int $id Client yelpBusinessId
     * @param int $today Today's date, formatted YYYYMMDD
     */
    public function createAndUploadCharts($s3Component, $bucket, $json, $id, $dateRange)
    {
        $phantom = Yii::app()->getComponent('phantomjs');
        
        // Clearly, this is the fake data used for testing new versions of the chart generator
        //$json = file_get_contents(Yii::app()->getComponent('s3Resource')->getResource("emailReport/fakeJson.json", 's3'));
        
        // This need to be set to be a blank page that PhantomJs can use for creating the charts
        // On testing, it appears as though having this as a remote file is too slow, and only *some* of the charts will get created
        $pathToBlankHtml = 'http://localhost/chart_images.html';
        
        // We need a writable path to temporarily create the images directory
        $options = '{"width":1000, "height":180, "imageDir":"' . Yii::app()->getBasePath() . '/tmp/emailReport/images/clientCharts/' . $id . '/' . $dateRange . '"}';
        
        // Set parameters and create the charts
        $phantom->setParams(array(
            $pathToBlankHtml,
            $json,
            $options,
        ));
        $phantom->execute('chart_generator.js');
        
        $charts = array();
        // Get the path where the charts are created locally so that they can be uploaded to S3
        $path = Yii::app()->getBasePath() . "/tmp/emailReport/images/clientCharts/$id/$dateRange";
        $directoryIterator = new DirectoryIterator($path);
        // Get the contents of the newly created images directory
        foreach ($directoryIterator as $file ) {
            if ($file->isFile() === TRUE && $file->getBasename() !== '.DS_Store') {
                if ($file->getExtension() == "png") {
                    array_push($charts, $file->getBasename());
                }
            }
        }
        
        // Upload newly created charts to S3
        foreach ($charts as $chart) {
            $chartToUpload = $path . "/$chart";
            $uri = "emailReport/images/clientCharts/$id/$dateRange/$chart";
            if (file_exists($chartToUpload)) {
                if (!$s3Component->putObject($s3Component->inputFile($chartToUpload), $bucket, $uri, S3::ACL_PUBLIC_READ)) {
                    throw new CException('Could not upload chart: ' . $chartToUpload);
                }
            }
        }
    }
    
    /**
     * Establish connection to S3, and created and upload charts, if necessary
     *
     * @param YelpAnalyticsStorage $yelpAnalyticsStorage YelpAnalyticsStorage instance
     * @param int $id Yelp business ID
     * @param int $today Today's date, formatted YYYYMMDD
     * @param int $startDate Data start date, formatted YYYYMMDD
     * @param int $endDate Data end date, formatted YYYYMMDD
     */
    public function handleS3($yelpAnalyticsStorage, $id, $today, $startDate, $endDate)
    {
        // Get the requested data range
        $combinedData = $yelpAnalyticsStorage->getData($id, $startDate, $endDate);
        $dateRange = $startDate.$endDate;
        // Encode to JSON for JS chart creation
        $json = CJSON::encode($combinedData);
        // Connect to S3
        $s3Component = Yii::app()->s3Component;
        $bucket = 'qm-static';
        // Get the contents of the QM bucket
        $bucketContents = $s3Component->getBucket($bucket);
        // If the charts for this client haven't been created yet for today, create and upload them
        if (!in_array("emailReport/images/clientCharts/$id/$dateRange/arpu_this_day.png", array_keys($bucketContents))) {
            $this->createAndUploadCharts($s3Component, $bucket, $json, $id, $dateRange);
            // Delete the local directory after the client charts have been uploaded to S3
            if (file_exists(Yii::app()->getBasePath() . "/tmp/emailReport/images/clientCharts/$id/$dateRange")) {
                $this->deleteLocalCharts(Yii::app()->getBasePath() . "/tmp/emailReport/images/clientCharts/$id/$dateRange");
            }
        }
        return $combinedData;
    }
    
    /**
     * Get a list of saved reports for this account manager
     * 
     * @return array Ten most recent email reports for this account manager
     */
    public function getSavedReports()
    {
        // Get all of the reports for this account manager
        if (isset($this->id) && !empty($this->id)) {
            // Don't include the current report
            return EmailReport::model()->recent()->findAll('accountManagerId = :id AND id != :currentReportId',
                array(':id' => Yii::app()->user->id,
                      ':currentReportId' => $this->id));
        } else {
            return EmailReport::model()->recent()->findAll('accountManagerId = :id', array(':id' => Yii::app()->user->id));
        }
    }
    
    /**
     * Set the user flash for retrieving a saved email report
     *
     * @param bool $created Whether this is a newly created email report
     * @param AccountManager $accountManager Current account manager
     */
    public function setFlashSavedEmailReport($created, $accountManager)
    {
        // Newly created email report
        if ($created) {
            Yii::app()->getUser()->setFlash('success', 'New email report saved!');
        // Viewing a past email report
        } elseif (isset($this->accountManager->id)
                  && ($this->accountManager->id == $accountManager->id)) {
            Yii::app()->getUser()->setFlash('success', 'Welcome back, '
                                            . ucfirst($accountManager->username)
                                            . '! You can send your previously saved reports from here.');
        // Mitigate full HTML XSS concerns
        } elseif (isset($this->accountManager->id)
                  && (!$this->accountManager->id == $accountManager->id)) {
            throw new CException('You can only view reports that you created');
        }
    }
    
    /**
     * Format the contents for a newly created email report and send it
     *
     * @param Photo $photo Client photo
     * @param int $id Client Yelp business ID
     * @param int $today Today's date, formatted YYYYMMDD
     * @return string Email body content
     */
    public function sendNewEmailReport($photo, $id, $startDate, $endDate)
    {
        $this->dateRange = $startDate . $endDate;
        
        // Set the model attributes based on submitted form
        $this->setAttributes($_POST['EmailReport'], false);
        // Format the email content (referencing images, formatting HTML, etc), based on whether text or graph is requested
        if ($this->textOrGraph == 'text') {
            // Format the email body for sending
            $emailContent = $this->formatEmailContent($this->attributes['emailContentText'], $photo);
        } else {
            // Get the embedded graphs
            $graphs = $this->getRequestedGraphs($this->attributes['emailContentGraph']);
            // Format the email body for sending
            $emailContent = $this->formatEmailContent($this->attributes['emailContentGraph'], $photo, $graphs, $id, $startDate, $endDate);
        }
        
        // Save the report and send it
        if ($this->save()) {
            // Set the standard mail parameters
            $mail = $this->setMailParameters();
            // Send the email
            $mail->MsgHTML($emailContent);
            $mail->send();
            
            // Get the ID of the email report insert into the DB
            return $this->id;
        }
    }
    
    /**
     * Send a previously saved email report
     */
    public function sendSavedEmailReport()
    {
        // Format the email content (embedded images, HTML, etc), based on whether text or graph is requested
        if ($this->textOrGraph == 'text') {
            // Only send the same view that was saved
            $emailContent = $this->emailContentText = $_POST['EmailReport']['emailContent'];
        } else {
            $emailContent = $this->emailContentGraph = $_POST['EmailReport']['emailContent'];
        }
        
        if ($this->save()) {
            // Set the standard mail parameters
            $mail = $this->setMailParameters();
            // Set the mail body as HTML
            $mail->MsgHTML($emailContent);
            // Send the email
            $mail->send()
                ? Yii::app()->getUser()->setFlash('success', 'Your email has been sent to: ' . $this->toAddress)
                : Yii::app()->getUser()->setFlash('failure', 'Unable to send email. Please contact the devs.');
        }
    }
}
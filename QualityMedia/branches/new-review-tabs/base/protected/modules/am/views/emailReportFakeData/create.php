<?php
/**
 * View email reports
 *
 * $this EmailReportForm
 * $id - Client ID
 * $yelpBusiness - YelpBusiness
 * $clientsForDropdown - Array of clients from which the dropdown menu will be created
 * $model - EmailReport instance
 * $startDate - Int start date, format YYYYMMDD
 * $endDate - Int end date, format YYYYMMDD
 * $data - Data from YelpAnalyticsStorage MongoDB
 * $availableDateRange - Array of first and last available Mongo document for this client
 * $photo - Photo instance for this client
 * $client - User instance for this client
 * $today - Int today's date, format YYYYMMDD
 * 
 * @author Logan Etherton <logan@qualitymedia.com>
 */
$this->pageTitle = 'Create an email report';
// Render the "Create" and "Sent" tabs
$this->renderPartial('/layouts/tabs/emailReportFakeData', array(
    'active' => 'createReport',
    'activity' => true,
    'id' => $id,
));

// Make sure there's some data available for this client
if ((isset($availableDateRange['firstDoc']) && !empty($availableDateRange['firstDoc']))
    && (isset($availableDateRange['lastDoc']) && !empty($availableDateRange['lastDoc']))
    ) {
    // Get the available doc date range for date selection
    $firstAvailableDoc = new DateTime($availableDateRange['firstDoc']);
    $firstAvailableDoc = $firstAvailableDoc->format('Y-m-d');
    $lastAvailableDoc = new DateTime($availableDateRange['lastDoc']);
    $lastAvailableDoc = $lastAvailableDoc->format('Y-m-d');
    
    // Format the start date
    $start = new DateTime($startDate);
    $startDateFormatted = $start->format('Y-m-d');
    $startDateFormattedWords = $start->format('M d, Y');
    $startDateFormattedDatepicker = $start->format('M-Y');
    // Format the end date
    $end = new DateTime($endDate);
    $endDateFormatted = $end->format('Y-m-d');
    $endDateFormattedWords = $end->format('M d, Y');
    $endDateFormattedDatePicker = $end->format('M-Y');
} else {
    // Set to null if there's no data yet for this client
    $firstAvailableDoc = null;
}
try {
    // This is here because I didn't properly plan for error handling
    $clientsForDropdown = $clientsForDropdown ?: $model->getClientsForDropdown(null);
    // Get all of the Yelp Businesses that should be displayed for this account manager
    $clientsForDropdown = EmailReportFakeData::formatClientsArrayForDropdown($clientsForDropdown);
    // Create the client drop down menu
    $clientNames = array('label'=>'Select Another Client');
    foreach ($clientsForDropdown as $clientDrop) {
        if ($clientDrop[0]->id == $id) {
            continue;
        }
        $yelpBusinessId = $clientDrop[0]->id;
            $clientNames['items'][] = array('label' => $clientDrop[0]->label,
                                            'url'   => $this->createUrl('emailReport/create/id/' . $yelpBusinessId));
    }
    
    // Format client label for display
    $remove = !empty($errorMessage) ?: strpos($yelpBusiness->label, ' (');
    $clientLabel = !empty($errorMessage) ?: substr($yelpBusiness->label, 0, $remove);
} catch (CException $e) {
    $errorMessage = $e->getMessage();
    $clientLabel = 'There are no clients available for this account manager';
    $clientNames = array('label' => 'No clients available');
}

?>
<style>
    .well {
        padding: 8px;
    }
    .radio.inline {
        position: relative;
        /*left: 2%;*/
        margin-left: 0.5%;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 1.2em;
    }
    .client-select {
        padding-top: 0.5%;
        padding-left: 0.5%;
    }
    .data-range {
        font-size: 1.1em;
        text-align: right;
    }
    .generate-report-header{
        /*font-size: 1.3em;*/
    }
    .middle-section{
        border-top: 1px solid rgb(239, 239, 239); box-shadow: -1px 0px 1px rgb(239, 239, 239);
        border-left: 1px solid rgb(239, 239, 239); box-shadow: -1px 0px 1px rgb(239, 239, 239);
        border-bottom: 1px solid rgb(239, 239, 239); box-shadow: -1px 0px 1px rgb(239, 239, 239);
        border-right: 1px solid rgb(239, 239, 239); box-shadow: -1px 0px 1px rgb(239, 239, 239);
    }
    .emailVerticalAlign{
        height: 3em;
    }
    .imageContainer img{
        height: 75px;
    }
    .items.tables{
        width: 100%;
    }
    input :focus{
        outline: 0;
        border: 0px;
        box-shadow: none;
    }
    input{
        outline: 0;
        border: 0px;
        box-shadow :none;
    }
</style>

<div id="main-content" class="gran-data row-fluid" style='color: rgb(51,51,51); padding: 0px;'>
    <div class='lst-table e-report child-content well' style='margin-bottom: 0px; position: relative; min-height: 500px;'>
        <!--Let the user know that something went wrong, and let them try again (make sure to repopulate the values from the current model)-->
        <div class='row-fluid'>
            <?php
            if (isset($_SESSION['emailError']) && !empty($_SESSION['emailError'])) {
                echo "<h1>{$_SESSION['emailError']}</h1>";
                unset($_SESSION['emailError']);
            }
            ?>
        </div>
        <!--Display the error summary-->
        <div class='row-fluid'>
            <?php
                echo CHtml::errorSummary($model);
            ?>
        </div>
        <!--Client email report heading-->
        <div class='row-fluid generate-report-header'>
            <table style="width:100%" cellpadding="">
                <tr>
                    <td style="width:55%;color:rgb(128, 128, 128);">
                        <h2 style='color: rgb(52,52,52);'><?php echo empty($errorMessage) ? "Generate email report for $clientLabel" : "Please select another client."; ?></h2>
                    </td>
                    <td style="width:45%; margin-right: 0px; text-align: right; font-size: 1.2em">
                        <?php
                        // Echo the available data range for this client
                        if ($firstAvailableDoc) {
                        ?>
                        Data is available from <?php echo $firstAvailableDoc;?> through <?php echo $lastAvailableDoc;?> for this client.
                        <?php
                        // If there's no data available, let the user know
                        } else { echo $errorMessage; } ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
            /**
             * Begin the main form
             */
            // Create the form action URL
            $startDateUrl = isset($startDate) ? '/startDate/' . $startDate : '';
            $endDateUrl = isset($endDate) ? '/endDate/' . $endDate : '';
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'method' => 'post',
                'action' => $this->createUrl('emailReportFakeData/create/id/' . $id . $startDateUrl . $endDateUrl),
                'enableAjaxValidation' => FALSE,
                'htmlOptions' => array('class' => 'well',
                                       'style' => 'background-color: white; padding: 0px',
                                       ),
            ));
            
            ?>
            <div class='row-fluid client-select' style='vertical-align: middle; padding-top: 15px; padding-left: 15px; padding-bottom: 15px'>
            <?php
            // Create the client dropdown
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type'=>'info',
                'size'=>'large',
                'buttons'=>array(
                    $clientNames
                ),
                'htmlOptions' => array(
                    'class' => 'btn-group bootstrap-select style span4',
                )
            ));
            ?>
            </div>
            <?php
            // Give an error if there's no data available for this client
            if (!$firstAvailableDoc) {
                $this->endWidget();
                echo $errorMessage;
            // Proceed with the form if there is data available for this client
            } else {
            ?>
        <hr>
        <div class='row-fluid' style='vertical-align: middle; padding-bottom: 1em'>
        <!--Custom range or 2 month default radio buttons-->
            <table style="font-size: 1.2em; margin-left: 1%">
                <tr>
                    <td style="width:255px;margin-top: 0px">
                        <input type="radio" name='dateRangeRadio' value="lastThirtyDays" checked='checked'></input>
                       Previous 2 months
                    </td>
                    <td>
                        <input type="radio" name='dateRangeRadio' value="customRange"></input>
                        Custom Monthly Range
                    </td>
                </tr>
            </table>
        </div>
        <div class='row-fluid' style='padding-bottom: 1em'>
            <!--Date pickers, now displaying month/year only-->
            <div id='datePicker' style='padding: 5px 0 10px 0; width: 100%'>
                <div style="width: 15%; height: auto; position: absolute; margin-left: 20%;">
                    <?php
                    echo $form->datepickerRow(
                        $model,
                        'dateEnd',
                        array(
                            'options' => array('language' => 'en'),
                            'prepend' => '<i class="icon-calendar"></i>',
                            'class' => 'input-append date',
                            'value' => $endDateFormattedDatePicker,
                            'format' => 'yyyymmdd',
                        )
                    );
                    ?>
                </div>
                <div style="width: 15%; height: auto; position: relative; margin-left: 1%">
                    <?php
                    echo $form->datepickerRow(
                        $model,
                        'dateStart',
                        array(
                            'options' => array('language' => 'en'),
                            'prepend' => '<i class="icon-calendar"></i>',
                            'class'   => 'input-append date',
                            'value' => $startDateFormattedDatepicker,
                            'format' => 'yyyymmdd',
                        )
                    );
                    ?>
                </div>
                <!--Remove this for the time being, needs to be redone into just months and years-->
                <!--<div style='margin-left: 3%'><a href='-->
                    <?php
                    //echo Yii::app()->createUrl('am/emailReport/create/id/' . $id . $startDateUrl . $endDateUrl);
                    ?>
                    <!--'>Update date range</a></div>-->
            </div>
        </div>
        <div class='row-fluid' style='vertical-align: middle;padding-bottom: 2em'>
        <!--Display either graph or text value radio buttons-->
            <table style="font-size: 1.2em; margin-left: 1%;">
                <tr>
                    <td style="width:255px;">
                        <input type="radio" id='displayGraphs' name='EmailReportFakeData[textOrGraph]' value="graph" checked='checked'></input>
                        Graphs
                    </td>
                    <td>
                        <input type="radio" id='displayText' name='EmailReportFakeData[textOrGraph]' value="text"></input>
                       Text values
                    </td>
                </tr>
            </table>
        </div>
        <!-- Generate report-->
        <div class="container-padding post-hd middle-section" style='line-height: 40px; font-size: 1.2em; padding-top: 0.2em; '>
            <div class="span2 ilabel" style='padding: 5px; padding-left: 2%; border-right: 1px solid rgb(239, 239, 239); box-shadow: 0px 0px 1px rgb(239, 239, 239);'>Generate Report</div>
            <a href="#"><div class="span2 iyelp" style='padding-top: 5px; padding-left: 2%; max-height:10px;color:#F57C6E'><?php echo CHtml::image($this->resourceUrl('/s3/images/yelp-icon-small.png', 'local')); ?></i> Yelp</div></a>
            <div class="clear"></div>
        </div>
        <!--Main email content-->
        <div style='width: 97%'>
            <!--Email recipient-->
            <div class='row-fluid e-report form-horizontal' style='padding-top: 1.5em; color: rgb(128, 128, 128)'>
                <div class='row-fluid span12'>
                    <div class="control-group emailVerticalAlign">
                        <label class="control-label" for="recipient" style='text-align: left; padding-left: 2%; width:5%''>To:</label>
                        <div class="controls">
                            <?php
                            echo CHtml::activeTextField($model, 'toAddress', array('class' => 'span12', 'value' => $client->attributes['email'])); echo $form->error($model,'email');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--Email sender-->
            <div class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128)'>
                <div class='row-fluid span12'>
                    <div class="control-group emailVerticalAlign">
                        <label class="control-label" for="sender" style='text-align: left; padding-left: 2%; width:5%''>From:</label>
                        <div class="controls">
                            <?php
                            echo CHtml::activeTextField($model, 'fromAddress', array('class' => 'span12', 'value' => $client->accountManager->attributes['firstName'] . ' ' . $client->accountManager->attributes['lastName'])); echo $form->error($model,'email');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--Email subject-->
            <div class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128)'>
                <div class='row-fluid span12'>
                    <div class="control-group emailVerticalAlign">
                        <label class="control-label" for="subject" style='text-align: left; padding-left: 2%; width:5%''>Subject:</label>
                        <div class="controls">
                            <?php
                            echo CHtml::activeTextField($model, 'subject', array('class' => 'span12', 'value' => 'Date range report')); echo CHTML::error($model,'subject');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--Email body graph display-->
            <div id='textReport' class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128); display: none'>
                <div class='row-fluid span12'>
                    <div class="control-group">
                        <label class="control-label" for="emailBody" style='text-align: left; padding-left: 2%; width:5%'>Body:</label>
                        <div class="controls" style='border: inset rgb(152,152,152); padding-bottom:10px'>
                            <div class='row-fluid'
                                 style="width: 99%; height: 1000px; vertical-align: middle; cursor: text; max-width: inherit">
                                <?php
                                    $emailContentText = $this->renderPartial('/emailReportFakeData/_contentText', array(
                                        'clientLabel' => $clientLabel,
                                        'id' => $id,
                                        'startDate' => $startDateFormatted,
                                        'endDate' => $endDateFormatted,
                                        'data' => $data,
                                        'startDateFormattedWords' => $startDateFormattedWords,
                                        'endDateFormattedWords' => $endDateFormattedWords,
                                        'photo' => $photo,
                                        'model' => $model,
                                        ),
                                        TRUE // Return the contents of the email to a var so it can be sent
                                    );
                                    
                                    echo CHtml::activeTextarea($model, 'emailContentText', array(
                                        'class' => 'wys hidden',
                                        'value' => $emailContentText,
                                        'style' => 'width: 100%; height: 1000px; overflow-y: scroll; vertical-align: middle; cursor: text',
                                        'id' => 'reportText',
                                        ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--Email body text display-->
            <div id='graphReport' class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128)'>
                <div class='row-fluid span12'>
                    <div class="control-group">
                        <label class="control-label" for="emailBody" style='text-align: left; padding-left: 2%; width:5%'>Body:</label>
                        <div class="controls" style='border: inset rgb(152,152,152); padding-bottom:10px'>
                            <div class='row-fluid'
                                 style="width: 99%; height: 1000px; vertical-align: middle; cursor: text; max-width: inherit">
                                <?php
                                    $emailContentGraph = $this->renderPartial('/emailReportFakeData/_contentGraph', array(
                                        'clientLabel' => $clientLabel,
                                        'id' => $id,
                                        'startDate' => $startDateFormatted,
                                        'endDate' => $endDateFormatted,
                                        'data' => $data,
                                        'startDateFormattedWords' => $startDateFormattedWords,
                                        'endDateFormattedWords' => $endDateFormattedWords,
                                        'photo' => $photo,
                                        'model' => $model,
                                        'today' => $today,
                                        ),
                                        TRUE // Return the contents of the email to a var so it can be sent
                                    );
                                    echo CHtml::activeTextarea($model, 'emailContentGraph', array(
                                        'class' => 'wys',
                                        'value' => $emailContentGraph,
                                        'style' => 'width: 100%; height: 1000px; overflow-y: scroll; vertical-align: middle; cursor: text',
                                        'id' => 'reportGraph',
                                        ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class='row-fluid e-report form-horizontal' style='padding-top: 1%; color: rgb(128, 128, 128)'>
                <div class='row-fluid span12'>
                    <div class="control-group emailVerticalAlign">
                        <div class="controls">
                            <?php
                            // Account manager ID and client ID for saving email reports
                            echo $form->hiddenField($model, 'accountManagerId', array('value' => Yii::app()->user->id));
                            echo $form->hiddenField($model, 'yelpBusinessId', array('value' => $id));
                            // Create the submit button
                            $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Send Email',
                                                                              'type' => 'primary'));
                            /**
                            * End the form
                            */
                            $this->endWidget();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Register the WYS script files
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('emailReport/wys-editor.js', 's3'));
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('emailReport/wys.js', 's3'));
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('emailReport/emailReport.js', 's3'));
}
<?php
/**
 * View email reports
 *
 * $this EmailReportForm
 * $id - yelpBusinessId from MySQL database
 * $clientsForDropdown - Used to create the drop down of client selections for this AM
 * $model - EmailReport for saving the form data
 *
 * @author Logan Etherton <logan@loganswalk.com>
 */
$this->pageTitle = 'View saved email reports';

// Render the "Create" and "Sent" tabs
$this->renderPartial('/layouts/tabs/emailReport', array(
    'active' => 'savedReports',
    'activity' => true,
    'id' => isset($model->yelpBusinessId) ? $model->yelpBusinessId : null,
));

// Create the client drop down menu
$dropdownReport = !empty($model->id) ? array('label'=>'Select another report') : array('label'=>'Select a report');

foreach ($savedReports as $savedReport) {
    $dropdownReport['items'][] = array('label' => $savedReport->subject,
                                       'url'   => $this->createUrl('emailReport/saved/emailReportId/' . $savedReport->id.($this->jrViewId ? "/jr/{$this->jrViewId}" : '')));
}

// Display the name of the current report
$reportLabel = !empty($model->subject) ? 'Saved report subject: "' . $model->subject . '"' : 'View a saved report';
$reportLabelBar = !empty($model->subject) ? 'Viewing saved report' : 'Select a saved report';

// Create the subject line from the selected report, if there is one
$subjectLine = isset($model->subject) ? $model->subject : '';

// Get the content of the saved report, whether it's text or graph display
if (!empty($model->textOrGraph)) {
    $emailContent = $model->textOrGraph == 'graph' ? $model->emailContentGraph : $model->emailContentText;
} else {
    $emailContent = null;
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
<div class='lst-table e-report gran-data child-content well' style='margin-bottom: 0px; position: relative; min-height: 500px;'>
    <!--Let the user know that something went wrong-->
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
                    <h2 style='color: rgb(52,52,52);'><?php echo $reportLabel; ?></h2>
                </td>
            </tr>
        </table>
    </div>
    <?php
        /**
         * Begin the main form
         */
        // Create the form action URL
        $emailReportIdUrl = isset($emailReportId) ? '/emailReportId/' . $emailReportId : '';
        $createdUrl = isset($created) ? '/created/true' : '';
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'method' => 'post',
            'action' => $this->createUrl('emailReport/saved' . $emailReportIdUrl . $createdUrl .($this->jrViewId ? "/jr/{$this->jrViewId}" : '')),
            'enableAjaxValidation' => FALSE,
            'htmlOptions' => array('class' => 'well',
                                   'style' => 'background-color: white; padding: 0px',
                                   ),
        ));

        ?>
        <div class='row-fluid client-select' style='vertical-align: middle; padding-top: 15px; padding-left: 15px'>
        <?php
        // Create the report selection dropdown
        $this->widget('bootstrap.widgets.TbButtonGroup', array(
            'type'=>'info',
            'size'=>'large',
            'buttons'=>array(
                $dropdownReport
            ),
            'htmlOptions' => array(
                'class' => 'btn-group bootstrap-select style span4',
            )
        ));
        ?>
        </div>
        <script>
            $(function(){
                $('.dropdown-menu').attr('style', 'overflow-y: auto; max-height: 200px');
            })
        </script>
        <br>
    <!--Main email content-->
    <div style='width: 97%'>
<?php
// If an email report is current selected, display it. If not, don't display this area
if (!empty($emailContent)) {
?>
        <!--Email recipient-->
        <div class='row-fluid e-report form-horizontal' style='padding-top: 1.5em; color: rgb(128, 128, 128)'>
            <div class='row-fluid span12'>
                <div class="control-group emailVerticalAlign">
                    <label class="control-label" for="recipient" style='text-align: left; padding-left: 2%; width:5%''>To:</label>
                    <div class="controls">
                        <?php
                        echo CHtml::activeTextField($model, 'toAddress', array('class' => 'span12', 'value' => 'Enter a recipient')); echo $form->error($model,'email');
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
                        echo CHtml::activeTextField($model, 'fromAddress', array('class' => 'span12', 'value' => $accountManager->email)); echo $form->error($model,'email');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!--Email subject-->
        <div class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128)'>
            <div class='row-fluid span12'>
                <div class="control-group emailVerticalAlign">
                    <label class="control-label" for="subjectLine" style='text-align: left; padding-left: 2%; width:5%''>Subject:</label>
                    <div class="controls">
                        <?php
                        echo CHtml::activeTextField($model, 'subject', array('class' => 'span12', 'value' => $subjectLine)); echo $form->error($model,'email');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!--Email body graph display-->
        <div id='savedReport' class='row-fluid e-report form-horizontal' style='color: rgb(128, 128, 128)'>
            <div class='row-fluid span12'>
                <div class="control-group">
                    <label class="control-label" for="emailBody" style='text-align: left; padding-left: 2%; width:5%'>Body:</label>
                    <div class="controls" style='border: inset rgb(152,152,152); padding-bottom:10px'>
                        <div class='row-fluid'
                             style="width: 99%; height: 1000px; vertical-align: middle; cursor: text; max-width: inherit">
                            <?php
                            echo CHtml::activeTextarea($model, 'emailContent', array(
                                'class' => 'wys',
                                'value' => $emailContent,
                                'style' => 'width: 100%; height: 1000px; overflow-y: scroll; vertical-align: middle; cursor: text',
                                'id' => 'reportText',
                                ));
                            echo $form->hiddenField($model, 'textOrGraph', array('value' => $model->textOrGraph));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    // Create the submit button if viewing a saved report
    if (!empty($model->image_attachment)) { ?>
        <div class='row-fluid e-report form-horizontal' style='padding-top: 1%; color: black'>
            <div class='row-fluid span12'>
                <label class="control-label" for="sender" style='text-align: left; padding-left: 2%; width:5%''>Attachment:</label>
                <div class="controls">
                    <a href="<?php echo $model->image_attachment;?>">
                    <?php
                    echo CHtml::image($model->image_attachment, '', array(
                            'style' => 'width: 100px; height: auto;',
                    ));
                    ?>
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
        <div class="row">

        </div>
<?php }
?>

        <div class='row-fluid e-report form-horizontal' style='padding-top: 1%; color: rgb(128, 128, 128)'>
            <div class='row-fluid span12'>
                <div class="control-group emailVerticalAlign">
                    <div class="controls">
                        <?php
                        // Create the submit button if viewing a saved report
                        if (isset($emailReportId) && !empty($emailReportId)) {
                            $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Send Email',
                                                                          'type' => 'primary'));
                        }
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
<script>
    $(function(){
        // Render the email body as WYSIHTML5
        if($('textarea.wys').length > 0){
            $('textarea.wys').wysihtml5();
        }
        // Hide the WYSIHTML toolbar
        $('.wysihtml5-toolbar').attr('hidden', 'hidden');
    });
</script>
<?php
// Register the WYS script files
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('emailReport/wys-editor.js', 's3'));
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('emailReport/wys.js', 's3'));
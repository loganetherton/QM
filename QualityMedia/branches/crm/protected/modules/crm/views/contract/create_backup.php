<?php
/**
 * View for contract creation
 *
 * @var $model Contract
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
$this->pageTitle = 'New contract';
echo CHtml::encode($this->renderPartial('/layouts/tabs/contractEntry', array(
  'active' => 'contracts',
  'activity' => true,
)));
?>
<!-- Present on S3, need to debug why it won't apply from there... -->
<div class="row-fluid" id="main-content">
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'post',
    'type' => 'horizontal',
    'inlineErrors' => true,
    'focus' => 'companyName',
    'action' => $this->createUrl(''),
    //'htmlOptions' => array(
    //                       'class' => 'well',
    //                       'style' => 'background-color: white; padding: 0px',
    //                       ),
));

?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">Client Details</h4>
        </div>
        <div class="panel-body">
            <!--<form class="form-horizontal">-->
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['companyName'][0]) ?
                            $form->labelEx($model, 'companyName') : $form->error($model, 'companyName');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'companyName', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['contactName'][0]) ?
                            $form->labelEx($model, 'contactName') : $form->error($model, 'contactName');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'contactName', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['contactTitle'][0]) ?
                            $form->labelEx($model, 'contactTitle') : $form->error($model, 'contactTitle');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'contactTitle', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['address1'][0]) ?
                            $form->labelEx($model, 'address1') : $form->error($model, 'address1');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'address1', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['address2'][0]) ?
                            $form->labelEx($model, 'address2') : $form->error($model, 'address2');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'address2', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['city'][0]) ?
                            $form->labelEx($model, 'city') : $form->error($model, 'city');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'city', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['state'][0]) ?
                            $form->labelEx($model, 'state') : $form->error($model, 'state');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'state', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['zip'][0]) ?
                            $form->labelEx($model, 'zip') : $form->error($model, 'zip');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'zip', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['country'][0]) ?
                            $form->labelEx($model, 'country') : $form->error($model, 'country');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'country', array('class' => 'span12')); ?>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['timeZone'][0]) ?
                            $form->labelEx($model, 'timeZone') : $form->error($model, 'timeZone');?></label>
                        <div class="controls">
                            <?php echo $form->dropDownList($model, 'timeZone', array('LA time, partying with movie stars', 'Mars time', 'Stop hitting me!'),
                                                           array('class' => 'selectpicker span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['businessType'][0]) ?
                            $form->labelEx($model, 'businessType') : $form->error($model, 'businessType');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'businessType', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['contractUrl'][0]) ?
                            $form->labelEx($model, 'contractUrl') : $form->error($model, 'contractUrl');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'contractUrl', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['phone'][0]) ?
                            $form->labelEx($model, 'phone') : $form->error($model, 'phone');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'phone', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label text-left"><?php echo empty($errors['phoneSecondary'][0]) ?
                            $form->labelEx($model, 'phoneSecondary') : $form->error($model, 'phoneSecondary');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'phoneSecondary', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['email'][0]) ?
                            $form->labelEx($model, 'email') : $form->error($model, 'email');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'email', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['bestTimeToReach'][0]) ?
                            $form->labelEx($model, 'bestTimeToReach') : $form->error($model, 'bestTimeToReach');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'bestTimeToReach', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <label class="control-label text-left"><?php echo empty($errors['clientUrl'][0]) ?
                            $form->labelEx($model, 'clientUrl') : $form->error($model, 'clientUrl');?></label>
                        <div class="controls">
                            <?php echo $form->textField($model, 'clientUrl', array('class' => 'span12')); ?>
                        </div>
                    </div>
                    <div class="control-group last-group">
                        <?php echo CHtml::label('Billing address is the same as client address', 'billingAddressSame', array('class' => 'control-label text-left'));?>
                        <div class="controls">
                            <?php echo CHtml::checkBox('billingAddressSame', false, array('id' => 'billingAddressSame')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span12">
            <div class="panel panel-primary">
                <div class="label label-info">Salesman Notes</div>
                <br /><br />
                <div class="panel-body">
                    <?php echo CHTML::activeTextArea($model, 'notesSales', array('class' => 'span12', 'rows' => 5)); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">Social Network Usage</h4>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="row-fluid">
                    <div class="span6">												
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['setupFee'][0]) ?
                            $form->labelEx($model, 'setupFee') : $form->error($model, 'setupFee');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'setupFee', array('class' => 'span4', 'value' => '200')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <fieldset>
                            <legend>Services:</legend>
                            <div class="row-fluid">
                                <div class="span3">
                                    <label> Yelp
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[yelp]', array('--', 1 => 'Yes'),
                                                                   array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Twitter
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[twitter]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Google+
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[googlePlus]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Facebook
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[facebook]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">
                                    <label> TripAdvisor
                                </label>
                                    <?php echo $form->dropDownList($contractSocialNetwork, 'type[tripAdvisor]', array('--', 1 => 'Yes'),
                                                                   array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Foursquare
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[foursquare]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> @Campaign
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[emailCampaign]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Social Star
                                </label>
                                <?php echo $form->dropDownList($contractSocialNetwork, 'type[socialStar]', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="span6">
                        <fieldset>
                            <legend>Trial Services:</legend>
                            <div class="row-fluid">
                                <div class="span3">
                                    <label> Yelp
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceYelp', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Twitter
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceTwitter', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Google+
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceGooglePlus', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Facebook
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceFacebook', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">
                                    <label> TripAdvisor
                                </label>
                                    <?php echo $form->dropDownList($model, 'trialServiceTripAdvisor', array('--', 1 => 'Yes'),
                                                                   array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> Foursquare
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceFoursquare', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                                <div class="span3">
                                    <label> @Campaign
                                </label>
                                <?php echo $form->dropDownList($model, 'trialServiceEmail', array('--', 1 => 'Yes'),
                                                               array('class' => 'select-picker span12')); ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div><br />
            </div>
        </div>
    </div>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">Contract Terms</h4>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['moneyBackGuarantee'][0]) ?
                                $form->labelEx($model, 'moneyBackGuarantee') : $form->error($model, 'moneyBackGuarantee');?></label>
                            <div class="controls">
                                <?php echo $form->dropDownList($model, 'moneyBackGuarantee', array(0 => 'No', 1 => 'Yes'),
                                                               array('class' => 'selectpicker span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['accountStatus'][0]) ?
                                $form->labelEx($model, 'accountStatus') : $form->error($model, 'accountStatus');?></label>
                            <div class="controls">
                                <?php echo $form->dropDownList($model, 'accountStatus',
                                                               array(0 => 'New Account, Needs Call and Email',
                                                                     1 => 'Sent Welcome Letter, Cannot Reach by Phone',
                                                                     3 => 'Not Contacted',
                                                                     4 => 'Actively Servicing – One Way Communication',
                                                                     5 => 'Actively Servicing – Two Way Communication',
                                                                     6 => 'Unable to Login – Wrong Account Credentials or Not Provided',
                                                                     7 => 'In Limbo – Client May Proceed with Caution',
                                                                     8 => 'Saved – Lower Monthly Rate, PAY EXTRA ATTENTION',
                                                                     9 => 'Cancelled',
                                                                     10 => 'Collections (Unserviceable Due to Non-Payment)'),
                                                               array('class' => 'selectpicker span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['dealType'][0]) ?
                                $form->labelEx($model, 'dealType') : $form->error($model, 'dealType');?></label>
                            <div class="controls">
                                <?php echo $form->dropDownList($model, 'dealType',
                                                               array(0 => 'Standard (2 or less services/single location)',
                                                                     1 => 'Elite (3 or more services and/or multi-location)',
                                                                     2 => '30 day trial (1-2 services for a 30 day trial period)'),
                                                               array('class' => 'selectpicker span12')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['paymentType'][0]) ?
                                $form->labelEx($model, 'paymentType') : $form->error($model, 'paymentType');?></label>
                            <!--<div class="controls">
                                <?php
                                //echo $form->radioButton($model,'paymentType',
                                //                              array('value' => 'creditCard', 'uncheckValue' => null, 'checked' => 'checked'));
                                ?>&nbsp;Credit Card<br>
                                <?php
                                //echo $form->radioButton($model,'paymentType', array('value' => 'check', 'uncheckValue' => null));
                                ?>&nbsp;Check
                            </div>-->
                            <div class="controls">
                                <?php echo $form->radioButtonList($model,'paymentType', array('creditCard' => 'Credit Card', 'check' => 'Check'), array('creditcard' => 'checked')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['contractDate'][0]) ?
                                $form->labelEx($model, 'contractDate') : $form->error($model, 'contractDate');?>
                            </label>
                            <?php
                            echo $form->datepickerRow(
                                $model,
                                'contractDate',
                                array(
                                    'options' => array('language' => 'en'),
                                    'prepend' => '<i class="icon-calendar"></i>',
                                    'class' => 'date',
                                    'format' => 'mm/dd/yyyy',
                                    'label'  => false,
                                )
                            );
                            ?>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['trialMaturityDate'][0]) ?
                                $form->labelEx($model, 'trialMaturityDate') : $form->error($model, 'trialMaturityDate');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'trialMaturityDate', array('class' => 'span12')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['welcomeCallTime'][0]) ?
                                $form->labelEx($model, 'welcomeCallTime') : $form->error($model, 'welcomeCallTime');?></label>
                                <?php
                                echo $form->datetimepickerRow(
                                    $model,
                                    'welcomeCallTime',
                                    array(
                                        'options' => array(
                                            'language' => 'en',
                                            'format' => 'mm/dd/yyyy hh:mm',
                                            'todayBtn' => true,
                                            'todayHighlight' => true,
                                            //'initialDate' => 'Date.now()'
                                        ),
                                        'prepend' => '<i class="icon-calendar"></i>',
                                        'class' => 'date',
                                        'label'  => false,
                                    )
                                );
                                ?>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['welcomeCallStatus'][0]) ?
                                $form->labelEx($model, 'welcomeCallStatus') : $form->error($model, 'welcomeCallStatus');?></label>
                            <div class="controls">
                                <?php echo $form->dropDownList($model, 'welcomeCallStatus',
                                                               array(0 => 'Not Completed',
                                                                     1 => 'Completed'),
                                                               array('class' => 'selectpicker span12')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    Yii::app()->clientScript->registerScript('datepicker', "
$('.date').datepicker({
        format: \"yyyy-M-d\",
    });
");
    ?>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">Credit Card Information</h4>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['cardholderName'][0]) ?
                                $form->labelEx($model, 'cardholderName') : $form->error($model, 'cardholderName');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'cardholderName', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['billingAddress1'][0]) ?
                                $form->labelEx($model, 'billingAddress1') : $form->error($model, 'billingAddress1');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'billingAddress1', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="control-group last-group">
                            <label class="control-label text-left"><?php echo empty($errors['billingAddress2'][0]) ?
                                $form->labelEx($model, 'billingAddress2') : $form->error($model, 'billingAddress2');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'billingAddress2', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="control-group ">
                                <label class="control-label text-left"><?php echo empty($errors['billingCity'][0]) ?
                                $form->labelEx($model, 'billingCity') : $form->error($model, 'billingCity');?></label>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'billingCity', array('class' => 'span12')); ?>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label text-left"><?php echo empty($errors['billingZip'][0]) ?
                                $form->labelEx($model, 'billingZip') : $form->error($model, 'billingZip');?></label>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'billingZip', array('class' => 'span12')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label text-left"><?php echo empty($errors['billingState'][0]) ?
                                    $form->labelEx($model, 'billingState') : $form->error($model, 'billingState');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'billingState', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="control-group last-group">
                            <label class="control-label text-left"><?php echo empty($errors['billingCountry'][0]) ?
                                    $form->labelEx($model, 'billingCountry') : $form->error($model, 'billingCountry');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'billingCountry', array('class' => 'span12')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6 creditCardControls">
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['creditCardType'][0]) ?
                                $form->labelEx($model, 'creditCardType') : $form->error($model, 'creditCardType');?></label>
                            <div class="controls">
                                <?php echo $form->dropDownList($model, 'creditCardType',
                                                               array(0 => 'Visa',
                                                                     1 => 'MasterCard',
                                                                     2 => 'Amex',
                                                                     3 => 'Discover'),
                                                               array('class' => 'selectpicker span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['ccNumber'][0]) ?
                            $form->labelEx($model, 'ccNumber') : $form->error($model, 'ccNumber');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'ccNumber', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['cvv'][0]) ?
                            $form->labelEx($model, 'cvv') : $form->error($model, 'cvv');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'cvv', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label text-left"><?php echo empty($errors['ccExpiration'][0]) ?
                                    $form->labelEx($model, 'ccExpiration') : $form->error($model, 'ccExpiration');?></label>
                            <div class="controls">
                                <?php echo $form->textField($model, 'ccExpiration', array('class' => 'span12')); ?>
                            </div>
                        </div>
                        <!--<div class="control-group last-group text-center">					
                                <br />
                                This isn't necessary here, is it?
                           <button class="btn btn-primary btn-large">CC Processed/Delete Card Info</button>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">Social Network Information</h4>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <select id="snType">
                    <?php
                    foreach (ContractSocialNetwork::model()->getValidTypes() as $type => $name)
                        echo '<option value="', $type, '">', $name, '</option>';
                    ?>
                </select>
                <?php
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'button',
                        'label' => 'Add Social Network',
                        'type' => 'success',
                        'htmlOptions' => array('class' => 'addSocialNetwork', 'style' => 'vertical-align: top;'),
                    ));
                ?>
                <div id="snTemplate_0" style="display: none; margin-top: 10px;">
                    <fieldset>
                        <legend class="bBlue snName"></legend>
                        <div class="row-fluid">
                            <div class="span4 authenticationControls">
                                <div class="control-group">
                                    <label class="control-label text-left" for="username">Username:</label>

                                    <div class="controls">
                                        <input type="text" class="span12" name="username" id="username"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label text-left" for="password">Password:</label>

                                    <div class="controls">
                                        <input type="text" class="span12" name="password" id="password"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label text-left" for="url">URL:</label>

                                    <div class="controls">
                                        <input type="text" class="span12" name="url" id="url">
                                    </div>
                                </div>
                            </div>
                            <div class="span4 yelpControls">
                                <div class="control-group">
                                    <label class="control-label text-left" for="advertise">Advertise With Yelp:</label>
                                    <div class="controls">
                                        <select name="advertise" id="advertise" class="selectpicker span12">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="signupControls">
                        <legend class="bBlue">On Signup:</legend>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label text-left" for="starRating">Star Rating:</label>
                                    <div class="controls">
                                        <select name="starRating" id="starRating" class="selectpicker span12">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label text-left" for="numReviews">No. of Reviews:</label>
                                    <div class="controls">
                                        <input name="numReviews" id="numReviews" type="text" class="span12">
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label text-left" for="numFilteredReviews">No. of Filtered Reviews:</label>
                                    <div class="controls">
                                        <input name="numFilteredReviews" id="numFilteredReviews" type="text" class="span12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <?php
                    $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'buttonType' => 'button',
                            'label' => 'Remove this network',
                            'type' => 'inverse',
                            'htmlOptions' => array('class' => 'btn-mini addSocialNetwork', 'style' => 'vertical-align: top;'),
                        )
                    );
                    ?>
                    <hr />
                </div>
            </div>
        </div>
    </div>
    <div class="button-submit text-center">
            <div class="span4 offset2">
                <?php
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array('buttonType'=>'submit',
                          'label'=>'Save Changes',
                          'type' => 'primary',
                          'htmlOptions' => array('class' => 'btn btn-large btn-warning btn-block'),
                          ));
                ?>
            </div>
            <div class="span4">
                <?php
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array('buttonType'=>'submit',
                          'label'=>'Submit',
                          'type' => 'primary',
                          'htmlOptions' => array('class' => 'btn btn-large btn-primary  btn-block'),
                          ));
                ?>
            </div>
        </div>
        <!--</form>-->
        <?php $this->endWidget();?>
    
</div>

<script type="text/javascript">
    socialNetworkNumber = 0;
    
    var addSocialNetwork = function(info)
    {
        // This function is only going to run once at is, since setting the snTemplate_x number isn't fully implemented. We can implement finish
        // implementing this if the AJAX method gives us problems.
        
        var $template = $('#snTemplate_' + socialNetworkNumber).clone();
        $template.attr("id","snTemplate_" + socialNetworkNumber + 1);
        
        for(var els = document.getElementById("snTemplate_" + socialNetworkNumber).getElementsByTagName('input'), i = els.length; i--;){
            els[i].name = "ContractSocialNetwork[" + socialNetworkNumber + "][" + els[i].name + "]";
        }
        
        $template.addClass('socialNetwork');
        $template.appendTo($('#snTemplate_' + socialNetworkNumber).parent()).show();
        var type = typeof info != 'undefined' ? info.type : $('select[id=snType]').val();
        var name = $('select[id=snType] > option[value=' + type + ']').text();

        $template.append($('<input type="hidden" name="type" />').val(type));
        $template.find('.snName').text(name);
        $template.find('.btn-inverse').click(function()
        {
            $template.remove();
        });

        if (type != <?php echo ContractSocialNetwork::TYPE_YELP ?>) {
            $template.find('.yelpControls').hide();
        }
        if (type == <?php echo ContractSocialNetwork::TYPE_FACEBOOK ?>
            || type == <?php echo ContractSocialNetwork::TYPE_TWITTER ?>
            || type == <?php echo ContractSocialNetwork::TYPE_SERVICEEMAIL ?>) {
            $template.find('.signupControls').hide();
        }
        if (type != <?php echo ContractSocialNetwork::TYPE_YELP ?>
            && type != <?php echo ContractSocialNetwork::TYPE_TWITTER ?>) {
            $template.find('.authenticationControls').hide();
        }

        if (typeof info != 'undefined')
        {
            for (i in info) {
                $template.find('[name=' + i + ']').val(info[i]);
            }
        }
        socialNetworkNumber++;
    };

    $('.addSocialNetwork').click(function(e)
    {
        addSocialNetwork();
        e.preventDefault();
        e.stopPropagation();
    });

    $('#main-content form').submit(function(e)
    {
        //e.preventDefault();
        
        
        var $form = $(this);
        $form.find('input[type=hidden].snField').remove();

        $('.socialNetwork').each(function(index, item)
        {
            $(item).find(':input').each(function(i, field)
            {
                var $field = $(field);

                $('<input />')
                    .attr('name', 'SocialNetworks[' + index + '][' + $field.attr('name') + ']')
                    .attr('type', 'hidden')
                    .val($field.val())
                    .addClass('snField')
                    .appendTo($form);
            });
        });
    });
    $('input[name=billingAddressSame]').click(function(e)
    {
        var addressLink = {
            'address1': 'billingAddress1',
            'address2': 'billingAddress2',
            'city':     'billingCity',
            'state':    'billingState',
            'zip':      'billingZip',
            'country':  'billingCountry'
        };

        for (i in addressLink) {
            if ($(this).is(':checked')) {
                $('input[name="Contract[' + addressLink[i] + ']"]')
                    .val(
                        $('input[name="Contract[' + i + ']"]:first')
                            .data('addressLink', 'Contract[' + addressLink[i] + ']')
                            .keyup(function(e)
                            {
                                $('input[name="' + $(this).data('addressLink') + '"]:first')
                                    .val($(this).val());
                            })
                            .val()
                    )
                    .attr('readonly', 'true');
            }
            else {
                $('input[name="Contract[' + i + ']"]:first').off('keyup');
                $('input[name="Contract[' + addressLink[i] + ']"]').removeAttr('readonly');
            }
        }
    });

    $('input[name="Contract[paymentType]"]').change(function(e)
    {
        if ($(this).val() == 'creditCard') {
            $('.creditCardControls').show();
        }
        else {
            $('.creditCardControls').hide();
        }
    });

    var socialnetworks = <?php echo CJSON::encode($socialNetworks) ?>;
    for (i in socialnetworks) {
        addSocialNetwork(socialnetworks[i]);
    }
</script>
<?php
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('bootstrap_v3/js/bootstrap-select.min.js', 's3'));
Yii::app()->clientScript->registerScriptFile($this->resourceUrl('bootstrap/js/bootstrap-datepicker.js', 's3'));
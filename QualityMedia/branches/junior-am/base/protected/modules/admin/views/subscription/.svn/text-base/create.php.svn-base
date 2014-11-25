<?php
    $this->setPageTitle('Create a new subscription');
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Overview'));

    $salesmen = new Salesman;
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id'=>'PricingForm',
                'type'=>'horizontal',
                'method'=>'post'
            )); ?>
            <div class="span12" id="errorsSummary" style="display: none">
                <span class="help-inline error"></span>
            </div>
            <div class="row-fluid form-step form-step-1" id="formStep1">
                <?php if($client): ?>
                    <h4>You are now creating a subscription for <?php echo $client->billingInfo->companyName; ?></h4>
                <?php else: ?>
                    <br /><span style="width: 135px; display: inline-block">Select Sales Rep.:</span>
                    <?php echo $form->dropDownList($salesmen, 'id', $salesmen->dropDownList('fullName'), array('style' => 'width: 240px')); ?>
                <?php endif; ?>
                <br />
                <h4>Pricing</h4>
                <span style="width: 135px; display: inline-block">Enter Plan Charges:</span>
                <?php echo $form->textField($model,'amount', array('placeholder' => 'Monthly Fee', 'class'=> 'input-small', 'style'=>"width: 98px")); ?>
                <?php echo $form->textField($model,'setupFee', array('placeholder' => 'One-time Setup Fee', 'class'=> 'input-small', 'style'=>"width: 118px")); ?>

                <br />
                <h4>Select Addons:</h4>

                <?php foreach($model->getAddons() as $addonCode => $addon): ?>
                    <br style="clear: both" />
                    <span style="width: <?php echo is_array($addon['amount']) ? '140' : '180';?>px; float: left; display: inline-block"><input value="<?php echo $addonCode; ?>" class="pull-left addonCheckbox" type="checkbox" id="PlanForm_<?php echo $addonCode; ?>" name="<?php echo is_array($addon['amount'])? 'AddonGroup[]': 'PlanForm[addons][]'; ?>" />
                        <label style="margin-left: 5px" class="pull-left" for="PlanForm_<?php echo $addonCode; ?>"><?php echo $addon['name']; ?><?php echo !is_array($addon['amount']) ? sprintf(" ($%s / mo)", $addon['amount']) : ''; ?></label>
                    </span>

                    <?php if(is_array($addon['amount'])): ?>
                        <select disabled="disabled" class="addonOptionSelect" id="PlanForm_<?php echo $addonCode; ?>_select" style="width: 240px; margin-top: -5px; display: none" name="PlanForm[addons][]">

                            <?php foreach($addon['amount'] as $addonOptionCode => $addonOption): ?>
                                <option value="<?php echo $addonOptionCode ?>"><?php echo $addonOption['optionName'].sprintf(" ($%s / mo)", $addonOption['amount']) ?></option>
                            <?php endforeach; ?>

                        </select>
                    <?php endif; ?>
                    <br style="clear: both" />
                <?php endforeach; ?>

                <div class="form-actions">
                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Next', 'id'=>'btnNext')); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id'=>'SubscriptionForm',
                'type'=>'horizontal',
                'method'=>'post'
            )); ?>
            <div class="row-fluid form-step form-step-2" id="formStep2" style="display: none">
                <?php if($model->hasErrors('recurlyError')): ?>
                    <?php echo $form->hiddenField($model,'recurlyError'); ?>
                    <?php echo $form->error($model,'recurlyError'); ?>

                    <p class="space-2"></p>
                    <?php endif; ?>
                    <h4>Plan:</h4>
                    <div id="planName">
                        &nbsp;
                    </div>
                    <h4>Addons:</h4>
                    <div id="addonsDescription">
                        &nbsp;
                    </div>
                    <p class="space-2"></p>
                    <div>
                        <div class="span6" style="width: 350px">
                            <h4>Billing Information</h4>

                            <?php
                            $businessNameOptions = array('placeholder' => 'Business Name', 'class'=> 'input-large');
                            $emailOptions = array('placeholder' => 'Email', 'class'=> 'input-large');
                            $firstNameOptions = array('placeholder' => 'First Name', 'class'=> 'input-large');
                            $lastNameOptions = array('placeholder' => 'Last Name', 'class'=> 'input-large');

                            if($client) {
                                $businessNameOptions['readonly'] = 'readonly';
                                $emailOptions['readonly'] = 'readonly';
                                $firstNameOptions['readonly'] = 'readonly';
                                $lastNameOptions['readonly'] = 'readonly';
                            }

                            echo $form->textField($model,'businessName', $businessNameOptions); ?><?php echo $form->error($model,'businessName'); ?><br />

                            <?php echo $form->textField($model,'email', $emailOptions); ?><?php echo $form->error($model,'Email'); ?><br />

                            <?php echo $form->textField($model,'firstName', $firstNameOptions); ?><?php echo $form->error($model,'firstName'); ?><br />

                            <?php echo $form->textField($model,'lastName', $lastNameOptions); ?><?php echo $form->error($model,'lastName'); ?><br />

                            <?php echo $form->hiddenField($model,'clientId'); ?><br />
                        </div>
                        <div class="span6">
                            <h4>Credit Card Information</h4>
                            <?php echo $form->textField($model,'cardNumber', array('placeholder' => 'Credit Card No.', 'class'=> 'input-large')); ?><?php echo $form->error($model,'cardNumber'); ?><br />
                            <?php echo $form->textField($model,'securityCode', array('placeholder' => 'CCV', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'securityCode'); ?><br />

                            <h4>Expiry Date</h4>
                            <?php echo $form->textField($model,'expirationMonth', array('placeholder' => 'MM', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'expirationMonth'); ?> / <?php echo $form->textField($model,'expirationDay', array('placeholder' => 'YY', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'expirationDay'); ?><br />
                        </div>
                    </div>
                    <br style="clear: both" />
                <div class="form-actions">
                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'size'=>'large', 'type'=>'info', 'label'=>'Back', 'id'=>'btnPrev')); ?>
                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Submit')); ?>
                </div>
            </div>
        <?php $this->endWidget(); ?>
            <div class="row-fluid form-step form-step-3" id="formStep3" style="display: none">
                <h2>Subscription has been added</h2>
                <a href="<?php echo $this->createUrl('/admin/subscription/create') ?>">Create a New Subscription</a>
            </div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('wizard', "

    var showFormStep = function(stepNo) {
        $('.form-step').slideUp('slow', function() {
            $('#errorsSummary').hide();
            $('#formStep'+stepNo).slideDown('slow');
        });
    }

    var loadAddonsDescription = function(selector) {
        var label = $(selector);

        var description = '';

        $('input.addonCheckbox').each(function() {

            if(this.checked) {
                description += $('label[for=\"'+$(this).attr('id')+'\"]').text();

                var relatedSelect = $('#'+$(this).attr('id')+'_select');

                if(relatedSelect.length) {
                    description += ': '+$('option:selected', relatedSelect).text();
                }

                description  += '<br />';

            }
        });

        label.html(description);
    }

    $(document).ready(function() {


        $('input.addonCheckbox').change(function() {
            var relatedSelect = $('#'+$(this).attr('id')+'_select');

            if(relatedSelect.length) {

                if(this.checked) {
                    relatedSelect.prop('disabled', false);
                    relatedSelect.show();
                }
                else {
                    relatedSelect.hide();
                    relatedSelect.prop('disabled', 'disabled');
                }
            }

            loadAddonsDescription('#addonsDescription');
        });

        $('.addonOptionSelect').change(function() {
            loadAddonsDescription('#addonsDescription');
        });


        $('#PricingForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                type   : 'post',
                'data' : $(this).serialize(),
                'url'  : '".$this->createUrl('ajaxFindPlanByPricing')."',
                'success': function(response, status) {
                    $('#planName').html(response);
                    showFormStep(2);
                },
                'error' : function(response) {
                    $('#errorsSummary').show();
                    $('.help-inline').html(response.responseText);
                }
            });
        });

        $('#SubscriptionForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type   : 'post',
                'data' : $('#PricingForm, #SubscriptionForm').serialize(),
                'url'  : '".$this->createUrl('ajaxSaveSubscription')."',
                'success': function(response, status) {

                    showFormStep(3);
                },
                'error' : function(response, data) {
                    $('#errorsSummary').show();

                    $('.help-inline').html(response.responseText);
                }
            });
        });

        $('#Plan_id').change(function() {
            if($(this).val() == '') {
                $('#PlanForm_amount, #PlanForm_setupFee').prop('disabled', false);;
            }
            else {
                $('#PlanForm_amount, #PlanForm_setupFee').prop('disabled', true);;
            }
        });

        $('#btnPrev').click(function() {
            showFormStep(1);
        });

    });
");
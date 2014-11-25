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
                <h4>Pricing</h4>
                <br /><span style="width: 135px; display: inline-block">Select Sales Rep.:</span>
                <?php echo $form->dropDownList($salesmen, 'id', $salesmen->dropDownList('fullName'), array('style' => 'width: 240px')); ?>
                <br />
                <span style="width: 135px; display: inline-block">Enter Plan Charges:</span>
                <?php echo $form->textField($model,'amount', array('placeholder' => 'Monthly Fee', 'class'=> 'input-small', 'style'=>"width: 98px")); ?>
                <?php echo $form->textField($model,'setupFee', array('placeholder' => 'One-time Setup Fee', 'class'=> 'input-small', 'style'=>"width: 118px")); ?>

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
                    <p class="space-2"></p>
                    <div>
                        <div class="span6">
                            <h4>Billing Information</h4>
                            <?php echo $form->textField($model,'businessName', array('placeholder' => 'Business Name', 'class'=> 'input-large')); ?><?php echo $form->error($model,'businessName'); ?><br />
                            <?php echo $form->textField($model,'email', array('placeholder' => 'Email', 'class'=> 'input-large')); ?><?php echo $form->error($model,'Email'); ?><br />

                            <?php echo $form->textField($model,'firstName', array('placeholder' => 'First Name', 'class'=> 'input-large')); ?><?php echo $form->error($model,'firstName'); ?><br />
                            <?php echo $form->textField($model,'lastName', array('placeholder' => 'Last Name', 'class'=> 'input-large')); ?><?php echo $form->error($model,'lastName'); ?><br />
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

    $(document).ready(function() {
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
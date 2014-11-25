<?php
    $this->setPageTitle('Choose a Plan');
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Overview'));
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'SubscriptionForm',
            'type'=>'horizontal',
            'method'=>'post'
        )); ?>

            <h4><?php printf('Client: %s | %s', CHtml::encode($client->getFullName(', ')), CHtml::encode($client->billingInfo->companyName)); ?></h4>

            <?php if(!$clientHasSalesman): ?>
                <h4>Select A Sales Rep</h4>
                <?php echo $form->dropDownList($client,'salesmanId',$salesmen->dropDownList('fullName'),array('label'=>'ad', 'prompt'=>'Select Sales Rep.')); ?>
            <?php else: ?>
                <h4>Sales Rep.: <?php echo $client->getSalesmanName('', ', '); ?></h4>
            <?php endif; ?>
            <h4>Select A Subsciption Plan</h4>
            <?php
             echo $form->dropDownList($plan, 'id', CHtml::listData($plan->findAll(array('order' => 'name')),'id','name')); ?>

            <p class="line-base"></p>

            <h4>Credit Card Information</h4>

            <?php if($model->hasErrors('recurlyError')): ?>
                <?php echo $form->hiddenField($model,'recurlyError'); ?>
                <?php echo $form->error($model,'recurlyError'); ?>

                <p class="space-2"></p>
            <?php endif; ?>

            <?php echo $form->textField($model,'cardNumber', array('placeholder' => 'Credit Card No.', 'class'=> 'input-large')); ?><?php echo $form->error($model,'cardNumber'); ?><br />
            <?php echo $form->textField($model,'securityCode', array('placeholder' => 'CCV', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'securityCode'); ?><br />

            <h4>Expiry Date</h4>
            <?php echo $form->textField($model,'expirationMonth', array('placeholder' => 'MM', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'expirationMonth'); ?><br />
            <?php echo $form->textField($model,'expirationDay', array('placeholder' => 'YY', 'class'=> 'input-mini')); ?><?php echo $form->error($model,'expirationDay'); ?><br />

            <div class="form-actions">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Submit')); ?>
            </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
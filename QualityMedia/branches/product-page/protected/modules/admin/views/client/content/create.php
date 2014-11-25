<div class="span12 row-fluid gran-data">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'            =>'client-form',
    'type'          =>'inline',
    'htmlOptions'   => array(
        'autocomplete' => 'off'
    ),
    'clientOptions' =>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <h2><i class="icon3-file-alt"></i> Company Information</h2>
    <div class="controls controls-row">
        <?php echo $form->error($model->client->billingInfo,'companyName'); ?>
        <?php echo $form->error($model->client,'email'); ?>
        <?php echo $form->error($model->client->billingInfo,'firstName'); ?>
        <?php echo $form->error($model->client->billingInfo,'lastName'); ?>
        <?php echo $form->error($model->client->billingInfo,'address1'); ?>
        <?php echo $form->error($model->client->billingInfo,'city'); ?>
        <?php echo $form->error($model->client->billingInfo,'zipCode'); ?>
        <?php echo $form->error($model->client->billingInfo,'state'); ?>
    </div>

    <div class="controls controls-row">
        <?php echo $form->textFieldRow($model->client->billingInfo,'companyName',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Business Name')); ?>
        <?php echo $form->textFieldRow($model->client,'email',array('class'=>'span3','autocomplete'=>'off')); ?>
    </div>

    <div class="controls controls-row">
        <?php echo $form->textFieldRow($model->client->billingInfo,'firstName',array('class'=>'span3','autocomplete'=>'off')); ?>
        <?php echo $form->textFieldRow($model->client->billingInfo,'lastName',array('class'=>'span3','autocomplete'=>'off')); ?>
    </div>

    <div class="controls controls-row">
        <?php echo $form->textFieldRow($model->client->billingInfo,'address1',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Street')); ?>
        <?php echo $form->textFieldRow($model->client->billingInfo,'city',array('class'=>'span3','autocomplete'=>'off')); ?>
    </div>

    <div class="controls controls-row">
        <?php echo $form->textFieldRow($model->client->billingInfo,'zipCode',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Zip')); ?>
        <?php echo $form->textFieldRow($model->client->billingInfo,'state',array('class'=>'span3','autocomplete'=>'off')); ?>
    </div>

    <p class="line-base"></p>

    <h2><i class="icon3-external-link-sign"></i> Login State</h2>

    <div class="controls controls-row">
        <?php
            echo $form->radioButtonList(
                $model->client,
                'status',
                $model->client->getStatuses(),
                array(
                    'template'=>'<label class="{labelCssClass}">{input}{label}</label> ',
                )
            );
        ?>
    </div>

<?php if(!$model->client->getIsNewRecord()): ?>
    <p class="line-base"></p>

    <h2><i class="icon3-external-link-sign"></i> Reset Password</h2>

    <div class="controls controls-row">
        
        <?php echo $form->passwordFieldRow($model->client,'password',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'New Password')); ?>
        <?php echo $form->passwordFieldRow($model->client,'verifyPassword',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Verify Password')); ?>

    </div>
<?php endif; ?>
    <p class="line-base"></p>

    <h2><i class="icon3-thumbs-up"></i> Social Networks</h2>

    <div class="controls controls-row">
        <div class="span1" style="margin-top: 5px">Yelp:</div>
        <div class="span2" style="width: 155px; margin-top: 5px">http://www.yelp.com/biz/</div> <div class="span3" style="margin: 0"><?php echo $form->textField($model->client->profile,'yelpId',array('autocomplete'=>'off','placeholder' => 'URL')); ?><?php echo $form->error($model,'yelpId'); ?></div>
        <?php echo $form->textFieldRow($model->client->profile,'yelpUsername',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Username')); ?>
        <?php echo $form->textFieldRow($model->client->profile,'yelpPassword',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Password')); ?>
    </div>

    <p class="line-base"></p>
    <?php if(!$model->client->getIsNewRecord() && (isset($accountManager) || isset($salesman))): ?>
        <?php if(isset($accountManager)): ?><h2><i class="icon3-user-md"></i> Account Manager: <?php echo ($accountManager?$accountManager->getFullName(', '):'None') ?></h2><?php endif; ?>
        <?php if(isset($salesman)): ?><h2><i class="icon3-user"></i> Sales Rep.: <?php echo ($salesman?$salesman->getFullName(', '):'None') ?></h2><?php endif; ?>
        <p class="line-base"></p>
    <?php endif; ?>

    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label'      => $model->client->getIsNewRecord() ? 'Add New Client' : 'Update Client',
        'buttonType' => 'submit',
        'size'       => 'large',
        'icon'       => 'icon3-globe',
        'htmlOptions'=> array('class'=>'btn-info'),
    )); ?>

<?php $this->endWidget(); ?>
</div>
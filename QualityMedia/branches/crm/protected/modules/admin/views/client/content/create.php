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
        <?php
            echo $form->error($model->client->billingInfo,'companyName');
            echo $form->error($model->client,'email');
            echo $form->error($model->client->billingInfo,'firstName');
            echo $form->error($model->client->billingInfo,'lastName');
            echo $form->error($model->client->billingInfo,'address1');
            echo $form->error($model->client->billingInfo,'city');
            echo $form->error($model->client->billingInfo,'zipCode');
            echo $form->error($model->client->billingInfo,'state');
        ?>
    </div>

    <div class="controls controls-row">
        <?php
            echo $form->textFieldRow($model->client->billingInfo,'companyName',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Business Name'));
            echo $form->textFieldRow($model->client,'email',array('class'=>'span3','autocomplete'=>'off'));
        ?>
    </div>

    <div class="controls controls-row">
        <?php
            echo $form->textFieldRow($model->client->billingInfo,'firstName',array('class'=>'span3','autocomplete'=>'off'));
            echo $form->textFieldRow($model->client->billingInfo,'lastName',array('class'=>'span3','autocomplete'=>'off'));
        ?>
    </div>

    <div class="controls controls-row">
        <?php
            echo $form->textFieldRow($model->client->billingInfo,'address1',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Address'));
            echo $form->textFieldRow($model->client->billingInfo,'address2',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Address 2'));
        ?>
    </div>

    <div class="controls controls-row">
        <?php
            echo $form->textFieldRow($model->client->billingInfo,'city',array('class'=>'span3','autocomplete'=>'off'));
            echo $form->textFieldRow($model->client->billingInfo,'state',array('class'=>'span3','autocomplete'=>'off'));
        ?>
    </div>

    <div class="controls controls-row">
        <?php
            echo $form->textFieldRow($model->client->billingInfo,'zipCode',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Zip'));
            echo $form->textFieldRow($model->client->billingInfo,'phone',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Phone'));
        ?>
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
            <?php
                echo $form->passwordFieldRow($model->client,'password',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'New Password'));
                echo $form->passwordFieldRow($model->client,'verifyPassword',array('class'=>'span3','autocomplete'=>'off', 'placeholder' => 'Verify Password'));
            ?>
        </div>
    <?php endif; ?>

    <p class="line-base"></p>

    <h2><i class="icon3-thumbs-up"></i> Social Networks</h2>

    <div class="controls controls-row">
        <div class="span1">Yelp:</div>

        <div class="span3">
            <?php echo $form->textFieldRow($model->client->profile,'yelpUsername',array('autocomplete'=>'off', 'placeholder' => 'Username')); ?>
        </div>

        <div class="span3">
            <?php echo $form->textFieldRow($model->client->profile,'yelpPassword',array('autocomplete'=>'off', 'placeholder' => 'Password')); ?>
        </div>

        <div class="span2">&nbsp;</div>
    </div>

    <div class="controls controls-row">
        <div class="span1">&nbsp;</div>
        <div class="span8">
            <p class="text-info">All available businesses will be fetched in background. Please come back in few minutes to review them.</p>
        </div>
    </div>

    <?php $yelpBusinesses = $model->yelpBusinesses; ?>
    <?php if(count($yelpBusinesses)): ?>
        <div id="target" style="background-color:#F7F7F9;">
            <div class="row-fluid">
                <!-- Changes in columns require changes in template partial -->
                <div class="span1">&nbsp</div>
                <div class="span1">Active</div>
                <div class="span3">Yelp ID</div>
                <div class="span3">Label</div>
                <div class="span4">&nbsp</div>
            </div>

            <div id="businesses-list">
                <?php
                    foreach($yelpBusinesses as $yelpBusiness) {
                        $this->renderPartial('content/_template', array('form'=>$form,'model'=>$yelpBusiness,'key'=>$yelpBusiness->bizId));
                    }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="controls controls-row">

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
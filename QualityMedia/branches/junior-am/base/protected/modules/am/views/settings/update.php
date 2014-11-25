<?php
    $this->setPageTitle('Change password');

    $this->renderPartial('/layouts/tabs/settings', array('active'=>'password'));
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'password-form',
            // 'type'=>'horizontal',
        )); ?>
            <h2><i class="icon3-user"></i> Edit Profile</h2>

            <div class="controls controls-row">
                <?php
                    echo $form->hiddenField($model,'username');
                    echo $form->passwordFieldRow($model,'oldPassword');
                    echo $form->passwordFieldRow($model,'password');
                    echo $form->passwordFieldRow($model,'verifyPassword');
                ?>
            </div>

            <div class="form-actions">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Update')); ?>
            </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<?php $this->setPageTitle('Change Password'); ?>
<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'change-password-form',
        'htmlOptions' => array('class' => 'well'),
    ));
    ?>

    <div>
        <?php
        echo $form->passwordFieldRow($model, 'oldPassword', array('class' => 'span3', 'size' => 20, 'maxlength' => 20));
        echo $form->passwordFieldRow($model, 'newPassword', array('class' => 'span3', 'size' => 20, 'maxlength' => 20));
        echo $form->passwordFieldRow($model, 'newPasswordConfirm', array('class' => 'span3', 'size' => 20, 'maxlength' => 20));
        ?>
    </div>

    <div class="submit">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Change Password')); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">
    $('#change-password-form').submit(function(){
        return confirm('Are you sure you want to change your password?');
    })
</script>
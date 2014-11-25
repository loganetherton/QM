<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

    <h2><i class="icon3-user"></i> <?php echo $legend; ?></h2>

    <fieldset>
        <?php
            echo $form->textFieldRow($model,'username');
            echo $form->textFieldRow($model,'firstName');
            echo $form->textFieldRow($model,'lastName');
            echo $form->passwordFieldRow($model,'password');
            echo $form->passwordFieldRow($model,'verifyPassword');
        ?>
    </fieldset>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Submit')); ?>
    </div>

<?php $this->endWidget(); ?>
<?php $this->setPageTitle('Sign in'); ?>

<div class="title-outside">
    <h2>Log In</h2>
    <p class="muted">Please fill out the following form with your login credentials</p>
</div>
<div class="child-content">
    <div id="main-content22" class="row-fluid">
        <div class="container-padding">

            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
                'id'=>'login-form',
                'type' => 'horizontal',
                'htmlOptions'=>array('class'=>'login'),
            )); ?>

                <div class="control-group">
                    <?php echo $form->labelEx($model,'username', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo $form->textField($model,'username',array('labelCssClass'=> 'test')); ?><br />
                        <?php echo $form->error($model,'username', array('style' => 'font-size: 12px')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo $form->labelEx($model,'password', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo $form->passwordField($model,'password',array('size'=>20,'maxlength'=>20)); ?><br />
                        <?php echo $form->error($model,'password', array('style' => 'font-size: 12px')); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <?php $this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'submit','type'=>'primary','label'=>'Login', 'htmlOptions' => array('class'=> 'flat'))); ?>
                    </div>
                </div>

            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>
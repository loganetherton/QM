<?php $this->setPageTitle('Sign in'); ?>

<?php ob_start(); ?>
    <h4>Log in</h4>
    <p>Please fill out the following form with your login credentials:</p>

    <div class="form">

        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'id'=>'login-form',
            'htmlOptions'=>array('class'=>'well'),
        )); ?>

            <div>
                <?php
                    echo $form->textFieldRow($model,'username',array('class'=>'span3'));
                    echo $form->passwordFieldRow($model,'password',array('class'=>'span3','size'=>20,'maxlength'=>20));
                ?>
            </div>

            <div class="submit">
                <?php $this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'submit','type'=>'primary','label'=>'Login')); ?>
            </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->

<?php
    $content = ob_get_contents();
    ob_end_clean();


$this->widget('bootstrap.widgets.TbBox', array(
    'title'=>'Sign in',
    'headerIcon'=>'icon-home',
    'content'=>$content,
));
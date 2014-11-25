<?php
Yii::app()->clientScript->registerScript('search', "
    $('.form-search').submit(function(){
        $.fn.yiiGridView.update('client-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
?>

<h2><i class="icon3-globe"></i> Find a Client</h2>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method'=>'get',
    'htmlOptions'=>array('class'=>'form-search'),
)); ?>

    <?php echo $form->textField($model,'fullName',array('class'=>'input-xxlarge', 'placeholder'=>'Enter text for quicksearch...')); ?>
    <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'info',
            'icon'=>'edit',
            'label'=>' Search',
            'htmlOptions'=>array('class'=>'search-button'),
        ));
    ?>

<?php $this->endWidget(); ?>

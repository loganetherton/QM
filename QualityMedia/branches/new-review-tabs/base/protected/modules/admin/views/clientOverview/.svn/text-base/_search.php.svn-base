<?php
Yii::app()->getClientScript()->registerScript('search', "
    $('.form-search').submit(function(){
        $.fn.yiiGridView.update('no-subscription-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
?>

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
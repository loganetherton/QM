<?php
Yii::app()->clientScript->registerScript('search', "
    $('.checkbox.inline input').change(function(){
        $.fn.yiiGridView.update('transaction-grid', {
            data: $(this).closest('form').serialize(),
            url: '".$this->createUrl('transaction/index')."'
        });
        return false;
    });
");
?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method'=>'get',
    'htmlOptions'=>array(
        'class'=>'form-search',
        'style'=>'margin-bottom:0px',
    ),
)); ?>
<input type="hidden" name="resetPagination" value="1" />
<?php
    echo $form->checkBoxList(
        $model,
        'status',
        $model->getStatuses(),
        array(
            'template'=>'<label class="{labelCssClass}">{input}{label}</label> ',
            'inline'=>true,
            // 'labelCssClass'=>'checkbox inline',
        )
    );
?>

<?php $this->endWidget(); ?>

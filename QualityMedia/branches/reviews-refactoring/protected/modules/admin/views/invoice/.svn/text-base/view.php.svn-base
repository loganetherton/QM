<?php $this->setPageTitle('View invoice'); ?>

<h3>Invoice: <?php echo CHtml::encode($model->number); ?></h3>

<div class="well">
    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label'=>'Back to invoices list',
        'size'=>'small',
        'url'=>array('invoice/index'),
        'icon'=>'arrow-left',
    )); ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'number',
        'uuid',
        'state',
        array(
            'name'=>'subtotal',
            'value'=>$model->getSubtotalAmount(),
        ),
        array(
            'name'=>'total',
            'value'=>$model->getTotalAmount(),
        ),
        'createdAt:datetime',
        'updatedAt:datetime',
    ),
));
?>

<hr />

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Download invoice PDF',
    'htmlOptions'=>array(
        'href'=>array('invoicePdf/view', 'id'=>$model->id),
    ),
    'url'=>array('invoicePdf/view', 'id'=>$model->id),
    'buttonType'=>'submitLink',
    'type'=>'success',
    'icon'=>'download white',
)); ?>
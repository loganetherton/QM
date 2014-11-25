<?php $this->setPageTitle($model->name); ?>

<h3><?php echo CHtml::encode($model->name); ?></h3>

<div class="well">
    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label'=>'Back to plans list',
        'size'=>'small',
        'url'=>array('plan/index'),
        'icon'=>'arrow-left',
    )); ?>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'planCode',
        'name',
        array(
            'name'  => 'amount',
            'value' => $model->getAmount(),
        ),
        array(
            'name'  => 'setupFee',
            'value' => $model->getSetupFee(),
        ),
        array(
            'label' => 'Custom Form Link',
            'type'  => 'raw',
            'value' => CHtml::Link('Go to custom form', $this->getCustomFormLink($model), array('target'=>'_blank')),
        ),
        'createdAt:date',
        'updatedAt:date',
    ),
)); ?>
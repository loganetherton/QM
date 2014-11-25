<?php $this->setPageTitle('View user'); ?>

<h3><?php echo CHtml::encode($model->email); ?></h3>

<div class="well">
    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label'=>'Back to users list',
        'size'=>'small',
        'url'=>array('user/index'),
        'icon'=>'arrow-left',
    )); ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'email',
        'billingInfo.firstName',
        'billingInfo.lastName',
        'billingInfo.companyName',
        'billingInfo.address1',
        'billingInfo.city',
        'billingInfo.state',
        'billingInfo.creditCard',
        'billingInfo.cardType',
        'createdAt:datetime',
        'updatedAt:datetime',
    ),
));
<?php $this->setPageTitle('View user'); ?>

<h3><?php echo CHtml::encode($model->email); ?></h3>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'email',
        'billingInfo.firstName',
        'billingInfo.lastName',
        'billingInfo.address1',
        'billingInfo.city',
        'billingInfo.state',
        'billingInfo.creditCard',
        'billingInfo.cardType',
    ),
));
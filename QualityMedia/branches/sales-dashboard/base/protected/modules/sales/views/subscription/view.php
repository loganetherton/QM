<?php $this->setPageTitle('View subscription'); ?>

<h3>Subscription: <?php echo CHtml::encode($model->uuid); ?></h3>

<div class="well">
    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label'=>'Back to subscriptions list',
        'size'=>'small',
        'url'=>array('subscription/index'),
        'icon'=>'arrow-left',
    )); ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'uuid',
        'user.email',
        'user.billingInfo.firstName',
        'user.billingInfo.lastName',
        'planCode',
        'planName',
        array(
            'name'=>'unitAmount',
            'value'=>$model->getAmount(),
        ),
        'quantity',
        'currency',
        'activatedAt:datetime',
        'canceledAt:datetime',
        'expiresAt:datetime',
        'currentPeriodStartedAt:datetime',
        'currentPeriodEndsAt:datetime',
        'trialStartedAt:datetime',
        'trialEndsAt:datetime',
        'state',
        'createdAt:datetime',
        'updatedAt:datetime',
    ),
));
?>

<hr />

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Cancel subscription',
    'htmlOptions'=>array(
        'href'=>array('subscriptionState/update', 'id'=>$model->id),
    ),
    'url'=>array('subscriptionState/update', 'id'=>$model->id),
    'buttonType'=>'submitLink',
    'type'=>'danger',
    'icon'=>'remove white',
)); ?>
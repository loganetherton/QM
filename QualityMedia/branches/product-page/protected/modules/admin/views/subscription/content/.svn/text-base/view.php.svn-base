<?php
$attributes = array(
    'id',
    'uuid',
    'user.email',
    'user.billingInfo.companyName',
    array(
        'name'=>'fullName',
        'value'=>$model->user->getFullName(', '),
    ),
    'planName',
    array(
        'name'=>'unitAmount',
        'value'=>'$'.number_format($model->getAmount(), 0),
    ),
    'activatedAt:datetime',
    'canceledAt:datetime',
    'expiresAt:datetime',
    'currentPeriodStartedAt:datetime',
    'currentPeriodEndsAt:datetime',
    array(
        'name'=>'state',
        'label'=>'Status',
    ),
    'createdAt:datetime',
    'updatedAt:datetime',
);

if($model->state != 'canceled') {
    unset($attributes['canceledAt:datetime']);
}
?>
<div class="gran-data row-fluid span6">
    <h3>Subscription Details</h3>
    <?php
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>$model,
        'type' => 'bordered',
        'htmlOptions' => array('class' => 'oview'),
        'attributes'=>$attributes,
    ));
    ?>

    <?php
    if($model->state == 'active') {
        $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Cancel subscription',
        'htmlOptions'=>array(
            'href'=>array('subscriptionState/update', 'id'=>$model->id),
        ),
        'url'=>array('subscriptionState/update', 'id'=>$model->id),
        'buttonType'=>'submitLink',
        'type'=>'danger',
        'icon'=>'remove white',
        ));
    }
    ?>
</div>

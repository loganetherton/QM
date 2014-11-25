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
        'value'=>'$'.number_format($model->getAmount(), 2),
    ),
    array(
        'label' => 'Setup Fee',
        'name'=>'unitAmount',
        'value'=>'$'.number_format($model->getSetupFee() / 100, 2),
    ),
);

if($model->hasAddons()) {
    array_push($attributes, array(
            'name'=>'addonsTotalAmount',
            'value'=>'$'.number_format($model->getAddonsTotalAmount(), 2),
        )
    );
}

$attributes = array_merge($attributes, array(
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
    )
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

    <?php if($model->hasAddons()): ?>
        <h3>Addons</h3>
        <?php
            $addons = new CArrayDataProvider(
                array_values(CJSON::decode($model->addonsDetails)),
                array(
                    'keyField' => 'addOnCode',
                )
            );

            $this->widget('bootstrap.widgets.TbJsonGridView', array(
                'dataProvider' => $addons,
                'type' => 'striped bordered condensed',
                'htmlOptions' => array('class' => 'oview'),
                'summaryText' => false,
                'columns' => array(
                    array(
                        'name' => 'name',
                        'header' => 'Addon Name',
                        'value' => '$data["name"]'
                    ),
                    array(
                        'name' => 'unitAmount',
                        'header' => 'Unit Amount',
                        'value' => '"$".number_format($data["unitAmount"] / 100, 2)'
                    )
                ),
            ));
        ?>
    <?php endif; ?>

    <?php
        if($model->isActive() || $model->isFuture()) {
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
<?php $this->setPageTitle('Subscriptions'); ?>

<h3>Subscriptions</h3>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider'=>$model->search(),
    'type'=>'condensed striped bordered',
    'template'=>'{items}{pager}',
    'columns'=>array(
        array(
            'name'=>'id',
            'header'=>'#',
            'htmlOptions'=>array('style'=>'width: 60px')
        ),
        'user.email',
        'user.billingInfo.firstName',
        'user.billingInfo.lastName',
        'state',
        'planCode',
        'quantity',
        'currency',
        'createdAt:datetime',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}',
        ),
    ),
));
<?php $this->setPageTitle('Users'); ?>

<h3>Users</h3>

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
        'email',
        'billingInfo.firstName',
        'billingInfo.lastName',
        'billingInfo.companyName',
        'createdAt:datetime',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}',
        ),
    ),
));
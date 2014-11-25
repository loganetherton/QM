<?php $this->setPageTitle('Unassigned users'); ?>

<h3>Unassigned users</h3>

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
            'template'=>'{assign}',
            'buttons'=>array(
                'assign'=>array(
                    'label'=>'Assign',
                    'url'=>'Yii::app()->getController()->createUrl("salesmanAssignment/update",array("id"=>$data->id))',
                    'icon'=>'plus',
                    'options'=>array(
                        'title'=>'Assign sales rep.',
                    ),
                ),
            ),
        ),
    ),
));
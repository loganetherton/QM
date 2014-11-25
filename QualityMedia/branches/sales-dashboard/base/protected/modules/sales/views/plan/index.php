<?php $this->setPageTitle('Plans'); ?>

<h3>Plans</h3>

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
        'planCode',
        'name',
        array(
            'name'  => 'amount',
            'value' => '$data->getAmount()',
        ),
        array(
            'name'  => 'setupFee',
            'value' => '$data->getSetupFee()',
        ),
        array(
            'class' => 'CLinkColumn',
            'label' => 'Go to custom form',
            'urlExpression' => array($this, 'getCustomFormLink'),
            'linkHtmlOptions' => array('target'=>'_blank'),
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}',
        ),
    ),
));
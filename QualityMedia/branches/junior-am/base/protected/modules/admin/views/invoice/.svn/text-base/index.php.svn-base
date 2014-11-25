<?php $this->setPageTitle('Invoices'); ?>

<h3>Invoices</h3>

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
        'uuid',
        'number',
        'state',
        array(
            'name'=>'subtotal',
            'value'=>'$data->getSubtotalAmount()',
        ),
        array(
            'name'=>'total',
            'value'=>'$data->getTotalAmount()',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}',
        ),
    ),
));
<div class="span12 row-fluid gran-data">
    <?php $this->widget('bootstrap.widgets.TbButton',array(
        'label' => 'Add a New Client',
        'type'  => 'info',
        'size'  => 'large',
        'icon'  => 'globe',
        'url'   => array('client/create'),
    )); ?>

    <?php $this->renderPartial('content/_search', array('model'=>$model)); ?>
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'client-grid',
        'dataProvider'=>$model->search(),
        'type'=>'bordered',
        'template'=>'{items}{pager}',
        'htmlOptions'=>array('style'=>'padding-top:0px; margin-left: 0px', 'class'=>'span8'),
        'itemsCssClass'=>'oview',
        'columns'=>array(
            array(
                'header' => 'Existing Clients',
                'name'  => 'billingInfo.companyName',
                'value' => '$data->billingInfo()->companyName',
            ),
            'createdAt:date',
            array(
                'class'=>'CLinkColumn',
                'label'=>' Edit Settings',
                'header'=> 'Action',
                'urlExpression'=>'array("client/update", "id"=>$data->id)',
            ),
        ),
    )); ?>
</div>
<?php $this->setPageTitle('Choose a Plan');
$this->renderPartial('/layouts/_tabs/billing', array('active'=>'Overview')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider'=>$model->search(),
            'type'=>'condensed striped bordered',
            'template'=>'{items}{pager}',
            'itemsCssClass'=>'oview',
            'columns'=>array(
                array(
                    'name'  => 'id',
                    'header' => '#',
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
                    'label' => 'Create a Subscription',
                    'urlExpression'=>'array("clientSubscription/create", "planId"=>$data->id, "clientId"=>'.$clientId.')',
                    'linkHtmlOptions' => array('target'=>'_blank'),
                ),
                'createdAt:date',
                'updatedAt:date',
            ),
        ));
        ?>
    </div>
</div>
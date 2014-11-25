<?php $this->setPageTitle('Employyes overview');
    $salesRepsWithoutSubscriptions = $model->getSalesRepsWithoutSubscriptions();
    $accountManagersWithNoCLients = $managerModel->getManagersWithNoCLients();
?>
<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Overview')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
		<h3>The following Sales Reps have no associated subscriptions to them</h3>
		<div class="row-fluid">
			<div class="span6">
	            <?php
	             $this->widget('bootstrap.widgets.TbGridView', array(
	                'dataProvider'=>$salesRepsWithoutSubscriptions,
	                'type'=>'bordered',
	                'template'=>'{items}{pager}',
	                'id' => 'saleRepsWithNoSubs',
	                'itemsCssClass' => 'oview',
	                'hideHeader' => true,
	                'columns'=>array(
	                    array(
	                    	'htmlOptions' =>array('style'=>'width: 30%'),
	                        'name'  => 'fullName',
	                        'value' => '$data->getFullName(", ")'
	                    ),
                        array(
                            'class'=>'CLinkColumn',
                            'htmlOptions' =>array('style'=>'width: 40%'),
                            'header'=>'Custom Forms',
                            'label'=>'Get Custom Form',
                            'urlExpression'=>'array("salesmanPlan/index", "id"=>$data->id)',
                        ),
	                    array(
	                        'type'=>'raw',
	                        'header'=> 'Action',
	                        'value' => 'CHtml::Link("View", Yii::app()->getController()->createUrl("salesman/view",array("id"=>$data->id)))." / ".CHtml::Link("Edit", Yii::app()->getController()->createUrl("salesman/update",array("id"=>$data->id)))'
	                    ),
	                ),
	            ));
	            ?>
			</div>
		</div>

		<h3>Set to receive only linked feeds, the following Account Managers have no links to clients</h3>
		<div class="row-fluid">
			<div class="span6">
	            <?php
	             $this->widget('bootstrap.widgets.TbGridView', array(
	                'dataProvider'=>$accountManagersWithNoCLients,
	                'type'=>'bordered',
	                'template'=>'{items}{pager}',
	                'id' => 'accountManagersWithNoLinks',
	                'itemsCssClass' => 'oview',
	                'hideHeader' => true,
	                'columns'=>array(
	                    array(
	                    	'htmlOptions' =>array('style'=>'width: 30%'),
	                        'name'  => 'fullName',
	                        'value' => '$data->getFullName(", ")'
	                    ),
                        array(
                            'class'=>'CLinkColumn',
                            'htmlOptions' =>array('style'=>'width: 40%'),
                            'header'=>'Custom Forms',
                            'label'=>'Get Custom Form',
                            'urlExpression'=>'array("accountManagerPlan/index", "id"=>$data->id)',
                        ),
                    array(
                        'type'=>'raw',
                        'header'=> 'Action',
                        'value' => 'CHtml::link("View", Yii::app()->getController()->createUrl("accountManager/view",array("id"=>$data->id)))." / ".CHtml::link("Edit", Yii::app()->getController()->createUrl("accountManager/update",array("id"=>$data->id)))'
                    ),
	                ),
	            ));
	            ?>
			</div>
		</div>
	</div>
</div>
<?php $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Overview')); ?>
<div id="main-content">
	<div class="span12 row-fluid gran-data">
		<h3>The following Clients do not have subscriptions</h3>
		<div class="row-fluid">
		    <?php $this->widget('bootstrap.widgets.TbGridView', array(
		        'dataProvider' =>$model->getUsersWithNoSubscription(),
		        'type'=>'bordered',
		        'template'=>'{items}{pager}',
		        'hideHeader' => true,
		        'htmlOptions'=>array('class' => 'span6'),
		        'itemsCssClass'=>'oview',
		        'columns'=>array(
		            'billingInfo.companyName',
		            array(
		                'class'=>'CLinkColumn',
		                'htmlOptions' => array('class' => 't_right', 'style'=>'width: 60%'),
		                'label'=>' Create a Subscription',
		                'urlExpression'=>'array("clientSubscription/create", "clientId"=>$data->id)',
		            ),
		        ),
		    ));
		    ?>
		</div>

		<h3>The following clients are not associated with any Sales Rep</h3>
		<div class="row-fluid">
		    <?php $this->widget('bootstrap.widgets.TbGridView', array(
		        'dataProvider' =>$model->unassignedSalesman()->search(),
		        'type'=>'bordered',
		        'template'=>'{items}{pager}',
		        'hideHeader' => true,
		        'htmlOptions'=>array('class' => 'span6'),
		        'itemsCssClass'=>'oview',
		        'columns'=>array(
		            'billingInfo.companyName',
		            array(
		                'class'=>'CLinkColumn',
		                'htmlOptions' => array('class' => 't_right', 'style'=>'width: 60%'),
		                'label'=>'Link Client to Sales Rep.',
		                'urlExpression'=>'array("salesmanAssignment/update", "id"=>$data->id)',
		            ),
		        ),
		    ));
		    ?>
		</div>

	</div>
</div>
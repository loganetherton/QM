<?php
    $this->setPageTitle('Clients overview');
    $this->renderPartial('/layouts/_tabs/client', array('active'=>'Overview'));
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <!-- Search box -->
        <h2><i class="icon3-rss"></i> Find A Client</h2>
        <?php $this->renderPartial('_search', array('model'=>$model)); ?>

        <h3>The following Clients do not have subscriptions</h3>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'no-subscription-grid',
                'dataProvider'=>$model->getUsersWithNoSubscription(),
                'ajaxUpdate'=>'no-subscription-grid, no-salesman-grid, no-am-grid, no-sm-grid',
                'type'=>'bordered',
                'hideHeader' => true,
                'template'=>'{items}{pager}',
                'htmlOptions'=>array('style'=>'padding-top:0px', 'class'=>'span6'),
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    'billingInfo.companyName',
                    array(
                        'class'=>'CLinkColumn',
                        'label'=>' Create a Subscription',
                        'htmlOptions' =>  array('target' => 'null', 'class' => 't_right', 'style'=>'width: 55%'),
                        'urlExpression'=>'array("subscription/create", "clientId"=>$data->id)',
                    ),
                ),
            ));
            ?>
        </div>

        <h3>The following Clients are not associated with any Sales Rep</h3>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'no-salesman-grid',
                'dataProvider'=>$model->getUsersWithNoSalesmanAssigned(),
                'ajaxUpdate'=>'no-subscription-grid, no-salesman-grid, no-am-grid, no-sm-grid',
                'type'=>'bordered',
                'hideHeader' => true,
                'template'=>'{items}{pager}',
                'htmlOptions'=>array('style'=>'padding-top:0px', 'class'=>'span6'),
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    'billingInfo.companyName',
                    array(
                        'class'=>'CLinkColumn',
                        'label'=>'Link Client to Sales Rep.',
                        'htmlOptions' =>  array('class' => 't_right', 'style'=>'width: 55%'),
                        'urlExpression'=>'array("salesmanAssignment/update", "id"=>$data->id)',
                    ),
                ),
            ));
            ?>
        </div>

        <h3>The following Clients are not associated with any Account Manager</h3>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'no-am-grid',
                'dataProvider'=>$model->getUsersWithNoAccountManagerAssigned(),
                'ajaxUpdate'=>'no-subscription-grid, no-salesman-grid, no-am-grid, no-sm-grid',
                'type'=>'bordered',
                'hideHeader' => true,
                'template'=>'{items}{pager}',
                'htmlOptions'=>array('style'=>'padding-top:0px', 'class'=>'span6'),
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    'billingInfo.companyName',
                    array(
                        'class'=>'CLinkColumn',
                        'label'=>'Link Client to Account Manager',
                        'htmlOptions' =>  array('class' => 't_right', 'style'=>'width: 55%'),
                        'urlExpression'=>'array("accountManagerAssignment/update", "id"=>$data->id)',
                    ),
                ),
            ));
            ?>
        </div>

        <h3>The following Clients have no Social Network Logins</h3>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'no-sm-grid',
                'dataProvider'=>$model->getUsersWithNoSocialNetworkAssigned(),
                'ajaxUpdate'=>'no-subscription-grid, no-salesman-grid, no-am-grid, no-sm-grid',
                'type'=>'bordered',
                'hideHeader' => true,
                'template'=>'{items}{pager}',
                'htmlOptions'=>array('style'=>'padding-top:0px', 'class'=>'span6'),
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    'billingInfo.companyName',
                    array(
                        'class'=>'CLinkColumn',
                        'label'=>'Add Social Network Login',
                        'htmlOptions' =>  array('class' => 't_right', 'style'=>'width: 55%'),
                        'urlExpression'=>'array("client/update", "id"=>$data->id)',
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>
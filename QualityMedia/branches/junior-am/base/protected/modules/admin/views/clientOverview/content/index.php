<div class="span12 row-fluid gran-data">
    <h3>The following Clients do not have subscriptions</h3>

    <div class="row-fluid">
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider'=>$model->getUsersWithNoSubscription(),
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
            'dataProvider'=>$model->getUsersWithNoSalesmanAssigned(),
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
            'dataProvider'=>$model->getUsersWithNoAccountManagerAssigned(),
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
            'dataProvider'=>$model->getUsersWithNoSocialNetworkAssigned(),
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
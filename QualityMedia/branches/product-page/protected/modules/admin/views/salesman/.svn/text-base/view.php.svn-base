<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Sales Reps')); ?>
<?php
$this->setPageTitle($model->getFullName(', '));
$salesmanRelatedSubscriptions = $subscriptionModel->salesmanScope($model->id)->search();
$subscriptionsTotal = $salesmanRelatedSubscriptions->totalItemCount;
?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <div class="span7 acc-info clearfix">
            <?php //now thumbnail disabled <span class="photo"><img src="http://placehold.it/76/76" width="76" height="76" /></span> ?>
            <div class="desc">
                <h2><?php echo $model->getFullName(', ') ?></h2>
                <h4>This Sales Rep has a total of <?php echo $subscriptionsTotal; ?> subscriptions linked to him</h4>
            </div>
        </div>
        <?php Yii::app()->clientScript->registerScript('search', "
            $(document).ready(function() {
                $('.unlinkClient').live('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $.get(url, function() {
                        $.fn.yiiGridView.update('linkedClients');
                    });
                });
            });
        ");
        ?>
        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$subscriptionModel->search(),
                'type'=>'bordered',
                'htmlOptions' => array('class'=>'span9'),
                'template'=>'{items}{pager}',
                'itemsCssClass'=>'oview',
                'id' => 'linkedClients',
                'columns'=>array(
                    array(
                        'name' => 'id',
                        'header' => 'Sub ID'
                    ),
                    'planName',
                    array(
                        //@todo add Origin field
                        'type'=>'raw',
                        'header' => 'Origin',
                        'value' => '"Custom Form"'
                    ),
                    'createdAt',
                    array(
                        'name' =>'user.billingInfo.companyName',
                        'header' => 'Client',
                    ),
                    array(
                        'name' =>'canceledAt',
                        'value' => '($data->state == "canceled"?$data->canceledAt:"")',
                        'header' => 'Date Canceled (if subscription is canceled)',
                    ),
                    array(
                        'name' =>'state',
                        'value' => 'ucfirst($data->state)',
                        'header' => 'Status',
                    ),
                    array(
                        'type'=>'raw',
                        'header'=> 'Action',
                        'value' => 'CHtml::link("Remove Link", array("ajaxUnlinkClient", "id"=>$data->user->id), array("class"=>"unlinkClient"));'
                    ),
                ),
            ));
            ?>
            </div>
        </div>
    </div>
</div>
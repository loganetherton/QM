<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Account Managers')); ?>
<?php
$linkedClients = $userModel->accountManagerScope($model->id)->search();
$clientsTotal = $linkedClients->totalItemCount;
?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <div class="span7 acc-info clearfix">
            <div class="desc">
                <h2><?php echo $model->getFullName(', ') ?></h2>
                <h4>This Account Manager has a total of <?php echo $clientsTotal; ?> clients linked to him</h4>
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
            <div class="span6">
                <?php $this->widget('bootstrap.widgets.TbDetailView', array(
                    'data'=>$model,
                    'type'=>'bordered',
                    'htmlOptions' => array('class' => 'oview'),
                    'attributes'=>array(
                        'username',
                        array(
                                'name'=>'fullName', 
                               'value'=>$model->getFullName(', '),
                            ),
                        array(
                            'name' => 'state',
                            'value' => ($model->state?'Enabled':'Disabled'),
                            'label'=>'Login State'
                        ),
                        array(
                            'name' => 'showOnlyLinkedFeeds',
                            'value' => ($model->showOnlyLinkedFeeds?'Yes':'All feeds'),
                            'label'=>'Show only linked feeds?'
                        ),
                        'createAt:date',
                    ),
                ));
                ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <h3>Just Linked</h3>
                <?php
                 $this->widget('bootstrap.widgets.TbGridView', array(
                    'dataProvider'=>$linkedClients,
                    'type'=>'bordered',
                    'id' => 'linkedClients',
                    'hideHeader' => true,
                    'template'=>'{items}{pager}',
                    'itemsCssClass' => 'oview',
                    'columns'=>array(
                        array(
                            'name'  => 'fullName',
                            'value' => '$data->getFullName(", ")." | ".$data->billingInfo->companyName',
                        ),
                        array(
                            'name'=>'enabled',
                            'type'=>'raw',
                            'value'=>'CHtml::link("Remove Link", array("ajaxUnlinkClient", "id"=>$data->id), array("class"=>"unlinkClient"));',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>                          
</div>
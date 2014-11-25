<?php
    $this->setPageTitle('Transaction Log');
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Transaction Log'));

    $dataProvider = $model->search();

    if(isset($_GET['resetPagination']) && isset($_GET['resetPagination']) == 1) {
        $dataProvider->getPagination()->setCurrentPage(0);
        unset($_GET['resetPagination']);
    }
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
    <?php $this->renderPartial('content/_search', array('model'=>$model)); ?>
        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'transaction-grid',
                'htmlOptions' => array('class' => 'span10'),
                'dataProvider'=>$dataProvider,
                'type'=>'bordered',
                'template'=>'{items}{pager}',
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    array(
                        'name'   => 'uuid',
                        'header' => 'Sub ID',
                    ),
                    'subscription.planName',
                    array(
                        'name'  => 'status',
                        'header'  => 'Description',
                        'value' => 'ucwords($data->status)',
                    ),
                    'createdAt:date',
                    array(
                        'name'   => 'user.billingInfo.companyName',
                        'header' => 'Client',
                    ),
                    array(
                        'name'     => 'user.salesman.fullName',
                        'value'    => '$data->user->getSalesmanName("None", ", ")',
                        'header'   => 'Sales Rep',
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>
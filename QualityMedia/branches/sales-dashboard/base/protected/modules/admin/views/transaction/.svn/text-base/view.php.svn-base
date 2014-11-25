<?php
    $this->setPageTitle('Transaction Log');
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Transaction Log'));
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'data'=>$model,
            'attributes'=>array(
                'id',
                'uuid',
                array(
                    'name'=>'amountInCents',
                    'value'=>$model->getAmount(),
                ),
                'currency',
                'action',
                'source',
                'status',
                'cvvResult',
                'avsResult',
                'avsResultStreet',
                'avsResultPostal',
                'transactionDate:datetime',
                'createdAt:datetime',
                'updatedAt:datetime',
            ),
        ));
        ?>
    </div>
</div>
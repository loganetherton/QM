<?php
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Subscriptions'));

    $dataProvider = $model->search();

    if(isset($_GET['resetPagination']) && isset($_GET['resetPagination']) == 1) {
        $dataProvider->getPagination()->setCurrentPage(0);
        unset($_GET['resetPagination']);
    }

?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
    <?php
        // Add a new subscription button
        $this->widget('bootstrap.widgets.TbButton',array(
            'label'=>' Add a New Subscription',
            'size'=>'large',
            'type'=>'info',
            'url'=>array('subscription/create'),
            'icon'=>'icon3-rss',
        ));

        // Download failed invoices csv report
        $this->widget('bootstrap.widgets.TbButton',array(
            'label'=>'Download Failed Invoices CSV Report',
            'size'=>'large',
            'type'=>'danger',
            'url'=>array('failedInvoice/create'),
            'icon'=>'icon3-time',
            'htmlOptions'=>array(
                'style'=>'margin-left:10px;',
            ),
        ));
    ?>
    <h2><i class="icon3-rss"></i> Find A Subscription</h2>
    <?php

    Yii::app()->clientScript->registerScript('search', "
        $('#Subscription_planCode').keyup(function(){
            $.fn.yiiGridView.update('subscriptions', {
                data: $(this).closest('form').serialize(),
                url: '".$this->createUrl('subscription/index')."'
            });
        });

        $('.checkbox.inline input').change(function(){
            $.fn.yiiGridView.update('subscriptions', {
                data: $(this).closest('form').serialize(),
                url: '".$this->createUrl('subscription/index')."'
            });
            return false;
        });
    ");
    ?>
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'method'=>'get',
        'htmlOptions'=>array('class'=>'form-search'),
    )); ?>

    <?php echo $form->textField($model,'planCode',array('class'=>'input-xxlarge pull-left', 'autocomplete'=>'off', 'placeholder'=>'Enter text for quicksearch...')); ?>

    <br style="clear: both" />
    <input type="hidden" name="resetPagination" value="1" />
    <div class="filters">
    <?php
        echo $form->checkBoxList(
            $model,
            'state',
            $model->getStatuses(),
            array(
                'template'=>'<label class="{labelCssClass}">{input}{label}</label> ',
                'inline'=>true,
                'labelCssClass'=>'checkbox inline',
                'style' => 'margin-left: 10px',
            )
        );
    ?>
    </div>
    <?php $this->endWidget(); ?>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'dataProvider'=> $dataProvider,
                'htmlOptions'=> array('class' => 'span9'),
                'type'=>'bordered',
                'id' => 'subscriptions',
                'template'=>'{items}{pager}',
                'itemsCssClass'=>'oview',
                'enableSorting'=> false,
                'columns'=>array(
                    array(
                        'header' => 'Company',
                        'name' => 'user.billingInfo.companyName'
                    ),
                    array(
                        'name' => 'startedAt',
                        'header' => 'Date Added',
                        'value' => 'date("Y/m/d",strtotime($data->startedAt))'
                    ),
                    array(
                        'name' => 'planName',
                        'header' => 'Plan Type',
                        'type' => 'raw',
                        'value' => '$data->getPlanDescription()'
                    ),
                    array(
                        'header' => 'Sales Rep',
                        'name'  => 'user.salesman.fullName',
                        'type'=>'HTML',
                        'value' => '($data->user->getSalesmanName())?$data->user->getSalesmanName("None", ", "):"None"',
                    ),
                    array(
                        'name'  => 'state',
                        'value' => 'ucwords($data->state)',
                    ),
                    array(
                        'type'=>'raw',
                        'header'=> 'Action',
                        'value' => 'CHtml::link("View", Yii::app()->getController()->createUrl("subscription/view",array("id"=>$data->id)))'
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>
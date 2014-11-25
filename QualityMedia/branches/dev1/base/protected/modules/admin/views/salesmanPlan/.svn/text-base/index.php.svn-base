<?php
    $title = sprintf('Custom subscription forms for %s', $salesman->getFullName(', '));
    $this->setPageTitle($title);
    $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Sales Reps'));
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h2><i class="icon3-list"></i> <?php echo CHtml::encode($title); ?></h2>

        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider'=>$model->search(),
            'type'=>'condensed striped bordered',
            'template'=>'{items}{pager}',
            'itemsCssClass'=>'oview',
            'columns'=>array(
                array(
                    'name'=>'id',
                    'header'=>'#',
                    'htmlOptions'=>array('style'=>'width: 60px')
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
                    'label' => 'Get Custom Form',
                    'urlExpression' => array($this, 'getCustomFormLink'),
                    'linkHtmlOptions' => array('target'=>'_blank'),
                ),
            ),
        )); ?>
    </div>
</div>
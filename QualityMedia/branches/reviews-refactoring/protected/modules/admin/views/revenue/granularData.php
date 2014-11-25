<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method'=>'post',
    'htmlOptions'=>array('class'=>'form-search'),
)); ?>
<?php $this->renderPartial('/layouts/_tabs/revenues', array('active'=>'Granular Data')); ?>
<div id="main-content">
	<div class="span12 row-fluid gran-data">
		<h2><i class="icon3-hdd"></i> Select Data</h2>
		<?php echo $form->checkBoxListInlineRow($model, 'showData', array('Revenues', 'Subscriptions', 'Cancellations'), array('label' => false)); ?>
	    <p class="line-base"></p>

	    <h2><i class="icon3-calendar"></i> Select Date Range</h2>
	            <?php echo $form->dateRangeRow($model, 'dateRange',
                array(
                'autocomplete' => 'off',
                'labelOptions' => array('style'=>'width: 85px'),
                'prepend'      =>'<i class="icon-calendar"></i>',
                )); ?>
	    <p class="line-base"></p>

	    <h2><i class="icon3-user"></i> Select Sales Reps</h2>
		<?php
		echo $form->dropDownList($model,'salesmenIds',$salesmen->dropDownList('fullName'),array('label'=>'ad', 'placeholder'=>'Enter text for quicksearch...', 'multiple'=>'multiple', 'class' => 'input-xxlarge')); 
		Yii::app()->bootstrap->registerAssetCss('select2.css');
		Yii::app()->bootstrap->registerAssetJs('select2.js');
		?>
		<script id="Script_Revenue_salesmenIds">
		$(document).ready(function() {
		$("#Revenue_salesmenIds").select2();
		});
		</script>
	    <p class="line-base"></p>

	    <h2><i class="icon3-list"></i> Select Sort Order</h2>
        <?php
            $sortOptions = array('DESC'=>'Highest to Lowest', 'ASC'=>'Lowest to Highest');
            echo $form->radioButtonList($model,'sortOrder',$sortOptions,array('template'=>'<div class="radioContainer">{input} {label}</div>'));
        ?>

	    <p class="line-base"></p>
	    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Submit')); ?>
		<p class="line-base"></p>

        <?php if(count($showDataOptions)): ?>
        <h2><?php echo ($model->dateFrom || $model->dateTo?'Time Period: '.$model->dateFrom.' - '.$model->dateTo:'All-Time') ?></h2>

        <?php endif; ?>
        <div class="row-fluid">
            <?php
            if(in_array('0', $showDataOptions)):
            $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$model->getSaleRepsStatsByRevenues(),
                'type'=>'bordered',
                'id' => 'ch-content',
                'htmlOptions' => array('class' => 'span4'),
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'header' => 'Sales Reps',
                        'htmlOptions' => array('class' => 'c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footer' => '<strong>Total</strong>',
                    ),
                    array(
                        'class'=>'TbTotalSumColumnCurrency',
                        'name' => 'revenues',
                        'header' => 'Revenues',
                        'htmlOptions' => array('class' => 'expand c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: center')
                    )
                ),
            ));
            endif;
            ?>
            <?php
            if(in_array('1', $showDataOptions)):
            $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$model->getSaleRepsStatsBySubscriptions(),
                'type'=>'bordered',
                'id' => 'ch-content',
                'htmlOptions' => array('class' => 'span4'),
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'header' => 'Sales Reps',
                        'value' => '$data->getFullName(", ")',
                        'htmlOptions' => array('class' => 'c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footer' => '<strong>Total</strong>',
                    ),
                    array(
                    	'name' => 'signups',
                        'class' => 'bootstrap.widgets.TbTotalSumColumn',
                        'header' => 'Subscriptions',
                    	'htmlOptions' => array('class' => 'expand c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: center')
                	)
                ),
            ));
            endif;
            ?>
            <?php
            if(in_array('2', $showDataOptions)):
            $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$model->getSaleRepsStatsByCancellations(),
                'type'=>'bordered',
                'id' => 'ch-content',
                'htmlOptions' => array('class' => 'span4'),
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'header' => 'Sales Reps',
                        'htmlOptions' => array('class' => 'c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footer' => '<strong>Total</strong>',
                    ),
                    array(
                    	'name' => 'cancellations',
                        'class' => 'bootstrap.widgets.TbTotalSumColumn',
                        'header' => 'Cancellations',
                    	'htmlOptions' => array('class' => 'expand c-blue'),
                        'headerHtmlOptions' => array('class' => 'top1'),
                        'footerHtmlOptions' => array('style' => 'font-weight: bold; text-align: center')
                	)
                ),
            ));
            endif;
            ?>
	</div>
</div>
<?php $this->endWidget(); ?>
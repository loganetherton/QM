<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Sales Reps')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
		<?php $this->setPageTitle('Add new sales rep.'); ?>

		<div class="well">
		    <?php $this->widget('bootstrap.widgets.TbButton',array(
		        'label'=>'Back to sales rep. list',
	            'size'=>'large',
	            'type'=>'info',
		        'url'=>array('salesman/index'),
		        'icon'=>'arrow-left',
		    )); ?>
		</div>

		<?php $this->renderPartial('_form', array('model'=>$model, 'legend'=>'Add new sales rep.')); ?>
	</div>
</div>
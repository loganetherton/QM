<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Account Managers')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
		<?php $this->setPageTitle('Add New account manager'); ?>

		<div class="well">
		    <?php $this->widget('bootstrap.widgets.TbButton',array(
		        'label'=>'Back to account managers list',
	            'size'=>'large',
	            'type'=>'info',
		        'url'=>array('accountManager/index'),
		        'icon'=>'arrow-left',
		    )); ?>
		</div>

		<?php $this->renderPartial('_form_create', array('model'=>$model, 'userModel'=>$userModel, 'amSearchModel'=>$amSearchModel, 'accountManagerModel'=>$accountManagerModel, 'searchModel'=>$searchModel, 'legend'=>'Add new account manager')); ?>
	</div>
</div>
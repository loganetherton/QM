<?php $this->setPageTitle('Client management'); ?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'tabs'=>array(
        array('label'=>'Overview', 'icon'=>'icon3-eye-open', 'url'=>array('clientOverview/index')),
        array(
            'label'=>'Manage Clients',
            'icon'=>'icon3-globe',
            'content'=>$this->renderPartial('content/index', array('model'=>$model), true),
            'active'=>true,
        ),
    ),
));
<?php $this->setPageTitle('Clients overview'); ?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'tabs'=>array(
        array(
            'label'=>'Overview',
            'icon'=>'icon3-eye-open',
            'content'=>$this->renderPartial('content/index', array('model'=>$model), true),
            'active'=>true,
        ),
        array('label'=>'Manage Clients', 'icon'=>'icon3-globe', 'url'=>array('client/index')),
    ),
));
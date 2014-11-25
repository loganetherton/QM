<?php $this->setPageTitle('Assign Account Manager to client'); ?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'tabs'=>array(
        array(
            'label'=>'Overview',
            'icon'=>'icon3-eye-open',
            'url'=>array('clientOverview/index'),
            'content'=>$this->renderPartial('content/update', array('model'=>$model, 'accountManagers'=>$accountManagers), true),
            'active'=>true,
        ),
        array('label'=>'Manage Clients', 'icon'=>'icon3-globe', 'url'=>array('client/index')),
    ),
));
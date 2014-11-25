<?php $this->setPageTitle('View subscription');

$this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'tabs'=>array(
        array('label'=>'Overview', 'icon'=>'icon3-eye-open', 'url'=>array('billingOverview/index')),
        array(
            'label'=>'Subscriptions',
            'icon'=>'icon3-rss',
            'url'=>array('subscription/index'),
            'content'=>$this->renderPartial('content/view', array('model'=>$model), true),
            'active'=>true,
        ),
        array('label'=>'Plans', 'icon'=>'icon3-inbox', 'url'=>array('plan/index')),
        array('label'=>'Transaction Log', 'icon'=>'icon3-list', 'url'=>array('transaction/index')),
    ),
));
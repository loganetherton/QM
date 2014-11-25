<?php $this->setPageTitle('Create Client'); ?>
<?php
$paramsArray = array('model'=>$model);
if(!$model->client->getIsNewRecord() && (isset($accountManager) || isset($salesman))) {
    if(isset($accountManager)) {
        $paramsArray = array_merge($paramsArray, array('accountManager'=>$accountManager));
    }
    if(isset($salesman)) {
        $paramsArray = array_merge($paramsArray, array('salesman'=>$salesman));
    }
}
?>
<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'tabs'=>array(
        array('label'=>'Overview', 'icon'=>'icon3-eye-open', 'url'=>array('clientOverview/index')),
        array('label'=>'Manage Clients', 'icon'=>'icon3-globe', 'url'=>array('client/index')),
        array(
            'label'     => $model->client->getIsNewRecord() ? 'Create Client' : 'Update Client',
            'icon'      => 'user',
            'content'   => $this->renderPartial('content/create', $paramsArray, true),
            'active'    => true,
        ),
    ),
));
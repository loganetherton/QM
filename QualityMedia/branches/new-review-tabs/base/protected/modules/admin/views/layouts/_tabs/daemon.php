<?php
$items = array(
    'Daemons Overview' => array('icon'=>'icon3-wrench', 'label'=>'Daemons Overview', 'url'=>$this->createUrl('/admin/daemon')),
    'Combined Logs' => array('icon'=>'icon3-eye-open', 'label'=>'Combined Logs', 'url'=>$this->createUrl('/admin/daemonCombinedLog')),
    'Daemon Tasks' => array('icon'=>'icon3-tasks', 'label'=>'Daemon Tasks', 'url'=>$this->createUrl('/admin/task')),
    'Daemon Logs' => array('icon'=>'icon3-folder-open', 'label'=>'Daemon Logs', 'url'=>'#', 'visible'=>false),
    'Daemon Task' => array('icon'=>'icon3-file', 'label'=>'Daemon Task', 'url'=>'#', 'visible'=>false),
);

// Set active item
if(isset($active) && $active && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
    $items[$active]['visible'] = true;
}

// Print a menu
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items'=>array_values($items)
));
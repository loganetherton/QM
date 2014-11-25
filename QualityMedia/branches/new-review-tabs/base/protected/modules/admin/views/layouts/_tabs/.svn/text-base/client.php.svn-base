<?php
$items = array(
    'Overview' => array('icon'=>'icon3-eye-open', 'label'=>'Overview', 'url'=>array('clientOverview/index')),
    'Manage Clients' => array('icon'=>'icon3-globe', 'label'=>'Manage Clients', 'url'=>array('client/index')),
);

// Set active item
if(isset($active) && $active && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

// Print a menu
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items' => array_values($items)
));
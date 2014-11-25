<?php
/**
 * Contracts tabs
 */
$items = array(
    'contracts'    => array('icon'=>'icon3-check', 'label'=>'Contracts', 'url'=>array('contract/create')),
    'contractsLive'    => array('icon'=>'icon3-briefcase', 'label'=>'Live Contracts', 'url'=>array('contract/create')),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));
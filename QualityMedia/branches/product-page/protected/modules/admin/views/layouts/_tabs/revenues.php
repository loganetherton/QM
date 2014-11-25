<?php
/**
 * Render Employees tabs menu
 */

$items = array(
	'Overview' => array('icon'=>'icon3-eye-open', 'label'=>'Overview', 'url'=>$this->createUrl('/admin/revenue')),
	'Granular Data' => array('icon'=>'icon3-ellipsis-horizontal', 'label'=>'Granular Data', 'url'=>$this->createUrl('/admin/revenue/granularData'))
);

//set active item
if(isset($active) && $active && in_array($active, array_keys($items))) {
	$items[$active]['itemOptions'] = array('class'=>'active');
}

//print a menu
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items' => array_values($items)
));
?>
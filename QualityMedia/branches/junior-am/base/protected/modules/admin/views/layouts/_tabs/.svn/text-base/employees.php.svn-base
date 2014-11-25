<?php
/**
 * Render Employees tabs menu
 */

$items = array(
	'Overview' => array('icon'=>'icon3-eye-open', 'label'=>'Overview', 'url'=>$this->createUrl('/admin/employee')),
	'Account Managers' => array('icon'=>'icon3-user-md', 'label'=>'Account Managers', 'url'=>$this->createUrl('/admin/accountManager')),
	'Sales Reps' => array('icon'=>'icon3-user', 'label'=>'Sales Reps', 'url'=>$this->createUrl('/admin/salesman')),
	'Administrators' => array('icon'=>'icon3-pencil', 'label'=>'Administrators', 'url'=>$this->createUrl('/admin/adminManagement'))
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
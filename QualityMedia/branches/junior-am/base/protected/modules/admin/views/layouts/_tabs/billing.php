<?php
/**
 * Render Billing Management tabs menu
 */

$items = array(
	'Overview' => array('icon'=>'icon3-eye-open', 'label'=>'Overview', 'url'=>$this->createUrl('/admin/billingOverview')),
	'Subscriptions' => array('icon'=>'icon3-rss', 'label'=>'Subscriptions', 'url'=>$this->createUrl('/admin/subscription')),
	'Plans' => array('icon'=>'icon3-inbox', 'label'=>'Plans', 'url'=>$this->createUrl('/admin/plan')),
	'Transaction Log' => array('icon'=>'icon3-list', 'label'=>'Transaction Log', 'url'=>$this->createUrl('/admin/transaction'))
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
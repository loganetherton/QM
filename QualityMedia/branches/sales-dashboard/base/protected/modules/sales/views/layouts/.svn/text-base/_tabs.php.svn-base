<?php

$items = array(
    'overview' => array('label'=>'Overview', 'url'=>array('/sales')),
    'addSubscription' => array('label'=>'Add New Contract/Subscription', 'url'=>array('subscription/new')),
    'pending' => array('label'=>'Pending Contracts Entered from Website &nbsp;  <span class="badge badge-important">120</span>', 'url'=>'#'),
    'clients' => array('label'=>'Client List', 'url'=>array('subscription/index')),
);

if($active && in_array($active, array_keys($items))) {
    $items[$active]['active'] = true;
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'        => 'tabs',
    'stacked'     => false,
    'encodeLabel' => false,
    'htmlOptions' => array('class'=> 'parent-tb2'),
    'items'=> array_values($items),
));
?>
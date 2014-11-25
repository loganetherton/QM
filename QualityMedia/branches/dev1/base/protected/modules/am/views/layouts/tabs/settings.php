<?php
/**
 * Settings section tabs.
 */
$items = array(
    'password' => array('icon'=>'icon3-cog', 'label'=>'Edit My Profile', 'url'=>array('settings/update')),
);

if(isset($active) && $active && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items' => array_values($items)
));

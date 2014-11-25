<?php
/**
 * Private messages section tabs.
 */
$items = array(
    'inbox'     => array('icon'=>'icon3-inbox', 'label'=>'Inbox', 'url'=>array('message/index', 'tab'=>'inbox')),
    'filtered'  => array('icon'=>'icon3-filter', 'label' => 'Inbox (Filtered)', 'url'=>array('message/index', 'tab'=>'filtered')),
    'sent'      => array('icon'=>'icon3-share-alt', 'label'=>'Sent', 'url'=>array('message/index', 'tab'=>'sent')),
    'archived'  => array('icon'=>'icon3-book', 'label'=>'Archived', 'url'=>array('message/index', 'tab'=>'archived')),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));

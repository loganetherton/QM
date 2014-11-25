<?php
/**
 * Client section tabs
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */
$items = array(
    'index' => array('icon' => 'icon3-inbox', 'label' => 'Manage Clients', 'url'=>array('clients/jr', 'id' => $accountManagerId)),
);

if (isset($active) && $active && in_array($active, array_keys($items)))
    $items[$active]['itemOptions'] = array('class'=>'active');

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items' => array_values($items)
));

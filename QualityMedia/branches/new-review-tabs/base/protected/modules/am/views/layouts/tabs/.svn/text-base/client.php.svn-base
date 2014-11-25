<?php
/**
 * Client section tabs
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */
$items = array(
    'index' => array('icon' => 'icon3-inbox', 'label' => 'Manage Clients', 'url'=>array('clients/index')),
    'notes' => array('icon' => 'icon3-pencil', 'label' => 'View Notes', 'url'=>array('notes/index')),
    'archived' => array('icon' => 'icon3-inbox', 'label' => 'Archived Notes', 'url' => array('notes/archived')),
);

if (isset($photos) && $photos)
    $items['photos'] = array('icon' => 'icon3-film', 'label' => 'View Photos', 'url' => array('photos/index', 'id' => $id));

if (isset($manage) && $manage)
    $items['manage'] = array('icon' => 'icon3-pencil', 'label' => 'Manage info', 'url' => array('manage/index', 'id' => $id));

if (isset($activity) && $activity) {
    $items += array(
        'user_views' => array('icon' => 'icon3-user', 'itemOptions' => array('class' => 'activity'), 'label' => 'User Views (' . Yii::app()->format->number($data['totals']['num_page_views']) . ')', 'url' => array('activity/view', 'id' => $id, 'show' => 1, 'period' => $period)),
        'leads_views' => array('icon' => 'icon3-user', 'itemOptions' => array('class' => 'activity'), 'label' => 'Customer Leads (' . Yii::app()->format->number($data['totals']['num_customer_actions']) . ')', 'url' => array('activity/view', 'id' => $id, 'show' => 2, 'period' => $period)),
        'revenue_views' => array('icon' => 'icon3-dollars', 'itemOptions' => array('class' => 'activity'), 'label' => 'Revenue Estimates ($' . Yii::app()->format->number($data['totals']['num_customer_actions'] * $arpu) . ')', 'url' => array('activity/view', 'id' => $id, 'show' => 3, 'period' => $period)),
    );
}

if (isset($active) && $active && in_array($active, array_keys($items)))
    $items[$active]['itemOptions'] = array('class'=>'active');

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs',
    'items' => array_values($items)
));

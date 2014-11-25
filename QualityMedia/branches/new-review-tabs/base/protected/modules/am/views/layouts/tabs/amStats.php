<?php
/**
 * Review section tabs.
 */
$items = array(
    'index'   => array('icon'=>'icon3-pencil', 'label'=>'Junior Activity', 'url'=>array('juniorAmActivity/index')),
);

if(Yii::app()->getUser()->isSuperSenior()) {
    $items['summary'] = array('icon'=>'icon3-tag', 'label'=>'Global View', 'url'=>array('juniorAmActivity/summary'));
}

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));
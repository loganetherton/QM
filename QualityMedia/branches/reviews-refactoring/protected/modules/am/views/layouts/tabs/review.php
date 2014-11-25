<?php
/**
 * Review section tabs.
 */
$items = array(
    'opened'    => array('icon'=>'icon3-pencil', 'label'=>'Newest Reviews / Comments', 'url'=>array('review/index', 'tab'=>'opened')),
    'replied'   => array('icon'=>'icon3-check', 'label'=>'Replied', 'url'=>array('review/index', 'tab'=>'replied')),
    'followup'  => array('icon'=>'icon3-bookmark', 'label'=>'Follow Up', 'url'=>array('review/index', 'tab'=>'followup')),
    'archived'  => array('icon'=>'icon3-book', 'label'=>'Archived', 'url'=>array('review/index', 'tab'=>'archived')),
    'flagged'   => array('icon'=>'icon3-flag', 'label'=>'Flagged', 'url'=>array('review/index', 'tab'=>'flagged')),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));

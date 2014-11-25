<?php
/**
 * Review section tabs.
 */
$items = array(
    'opened'    => array('icon'=>'icon3-pencil', 'label'=>'Newest Reviews', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'opened')),
    'filtered'  => array('icon'=>'icon3-filter', 'label'=>'Filtered Reviews', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'filtered')),
    'replied'   => array('icon'=>'icon3-check', 'label'=>'Replied', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'replied')),
    'followup'  => array('icon'=>'icon3-bookmark', 'label'=>'Follow Up', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'followup')),
    'archived'  => array('icon'=>'icon3-book', 'label'=>'Archived', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'archived')),
    'flagged'   => array('icon'=>'icon3-flag', 'label'=>'Flagged', 'url'=>array('review/jr', 'id'=> $id, 'tab'=>'flagged')),
    'notes'     => array('icon'=>'icon3-pencil', 'label' => 'View Notes', 'url' => array('notes/index', 'type' => 'review')),
    'archived_notes'  => array('icon'=>'icon3-inbox', 'label' => 'Archived Notes', 'url' => array('notes/index/archived/true', 'type' => 'review')),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));

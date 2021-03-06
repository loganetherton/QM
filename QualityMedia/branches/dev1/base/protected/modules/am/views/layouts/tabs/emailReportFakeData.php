<?php
/**
 * Email report section tabs.
 */
$emailReportIdUrl = isset($emailReportId) ? '/emailReportId/' . $emailReportId : '';

$items = array(
    'createReport'    => array('icon'=>'icon3-pencil', 'label'=>'Create Report', 'url'=>array('emailReportFakeData/create/id/' . $id)),
    'savedReports'  => array('icon'=>'icon3-filter', 'label'=>'Saved Reports', 'url'=>array('emailReportFakeData/saved' . $emailReportIdUrl)),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));
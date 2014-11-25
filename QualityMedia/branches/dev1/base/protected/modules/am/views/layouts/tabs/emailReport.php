<?php
/**
 * Email report section tabs.
 */
$emailReportIdUrl = isset($emailReportId) ? '/emailReportId/' . $emailReportId : '';

$jrViewUrlSuffix = ($this->jrViewId ? "/jr/{$this->jrViewId}" : '');

$items = array(
    'createReport'    => array('icon'=>'icon3-pencil', 'label'=>'Create Report', 'url'=>array('emailReport/create/id/' . $id . $jrViewUrlSuffix)),
    'savedReports'  => array('icon'=>'icon3-filter', 'label'=>'Saved Reports', 'url'=>array('emailReport/saved' . $emailReportIdUrl . $jrViewUrlSuffix)),
);

if(isset($active) && in_array($active, array_keys($items))) {
    $items[$active]['itemOptions'] = array('class'=>'active');
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'  => 'tabs',
    'items' => array_values($items),
));
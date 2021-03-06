<?php
// Format the title for each item for the notes section
$titleBeforeParentheses = strpos($title, ' (');
$titleForNotes = strtolower(substr($title, 0, $titleBeforeParentheses));
$titleForImage = preg_replace('/\s/', '', $titleForNotes);
// Create all month formattings
$dateOne = new DateTime($startDate);
$monthOne = $dateOne->format('Ym');
$dateOne = $dateOne->format('M Y');
$dateTwo = new DateTime($endDate);
$monthTwo = $dateTwo->format('Ym');
$dateTwo = $dateTwo->format('M Y');
// Get the values and average for data points in the two months
$monthOneValues = $model->findValuesAssociatedWithMonth($data, $monthOne);
$monthOneAverage = (int)round(array_sum($monthOneValues) / count($monthOneValues));
//$monthOneAverage = end($monthOneValues);
$monthTwoValues = $model->findValuesAssociatedWithMonth($data, $monthTwo);
$monthTwoAverage = (int)round(array_sum($monthTwoValues) / count($monthTwoValues));
//$monthTwoAverage = end($monthTwoValues);
// Determine the difference between the two monthly averages
$delta = $monthTwoAverage - $monthOneAverage;
?>
<tr>
    <td colspan="2" style="border-top:1px solid #fff;border-bottom: 1px solid #fff;width:70%;background:#efefef;padding:10px;font-size:17px">
        <b><?php echo $title; ?></b>
    </td>
    <?php
    // Display header for negative trends
    if ($delta < 0) {
    ?>
    <td style="border-top:1px solid #fff;border-bottom:1px solid #fff;width:10%;background:#D7EBF4;padding:5px 10px;vertical-align:middle;font-size:11px;line-height:11px; background: none repeat scroll 0% 0% rgb(245, 223, 223)">
        <?php echo $dateOne . ' - ' . $dateTwo?>
    </td>
    <td style="border-top:1px solid #fff;border-bottom:1px solid #fff;width:15%;background:#D7EBF4;padding:5px 10px;vertical-align:middle;text-align:right;color:#289BFD; background: none repeat scroll 0% 0% rgb(245, 223, 223)"><?php echo CHtml::image($this->resourceUrl("emailReport/images/arrow-down.png", 's3'), 'arrow-down'); ?>
    </td>
    <?php
    // Display header for positive trends
    } else {
    ?>
    <td style="border-top:1px solid #fff;border-bottom: 1px solid #fff;width:10%;background:#D7EBF4;padding:5px 10px;vertical-align:middle;font-size:11px;line-height:11px">
        <?php echo $dateOne . ' - ' . $dateTwo?>
    </td>
    <td style="border-top: 1px solid #fff;border-bottom: 1px solid #fff;width:15%;background:#D7EBF4;padding:5px 10px;vertical-align:middle;text-align:right;color:#289BFD;">
        <?php if ($delta != 0) {echo CHtml::image($this->resourceUrl("emailReport/images/arrow-up.png", 's3'), 'arrow-up');} else { echo 'No change';} ?>
    </td>
    <?php } ?>
</tr>
<!--Display text representation of data-->
<tr style="width:100%;background:#efefef;padding:0px;border-bottom: 1px solid #fff">
    <td style="border:0px solid #fff;background:#f6f6f6;padding:0px" colspan="4">
        <?php echo CHtml::image($this->resourceUrl("emailReport/images/clientCharts/fakeData/$graphName.png", 's3'), "graph-$graphName",
                                array('width' => '1200')); ?>
    </td>
</tr>
<tr style="width:100%;background:#efefef;padding:0px;font-size:10px">
    <td colspan="4" style="border:0px solid #fff;width:100%;background:white;padding:0px;font-size:13px;" cellpadding="">
        <div style='margin-left:5%; margin-top: 5px'>
            Place notes here for <?php echo $titleForNotes;?>
        </div>
    </td>
</tr>
    </td>
</tr>
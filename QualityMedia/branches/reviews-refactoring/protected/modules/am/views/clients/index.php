<?php
/**
 * Client index view
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('Manage Clients');
$this->renderPartial('/layouts/tabs/client', array('active' => 'index'));
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
<?php
$this->widget('ClientGridView', array(
    'afterAjaxUpdate' => '
        function(id, data)
        {
            jQuery("input[name=\"Note[dueAt]\"]").datepicker({
                "language":"en",
                "format":"mm/dd/yyyy",
                "weekStart":0
            });
        }',
    'dataProvider' => $data,
    'type' => 'bordered',
    'template' => '{items}{pager}',
    'id' => 'clientList',
    'enablePagination' => true,
    'rowCssClassExpression' => '$data->isFlagged() ? "error" : ""',
    'rowHtmlOptionsExpression' => 'array("id" => "client-" . $data->id)',
    'additionalRowTemplate' => '_client',
    'columns' => array(
        array(
            'name' => 'companyName',
            'value' => '$data->billingInfo->companyName',
            'header' => 'Client',
            'headerHtmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'name' => 'createdAt',
            'value' => 'date("m/d/y", strtotime($data->createdAt))',
        ),
        array(
            'class' => 'ClientDataColumn',
            'name' => 'reviewsCount',
            'value' => '!empty($data->profile) && !empty($data->profile->yelpUsername) ? $data->profile->yelpReviewsCount : "Yelp account not linked"',
            'header' => 'Yelp Reviews <span class="caret"></span>',
            'htmlOptions' => array(
                'colspan' => 'empty($data->profile->yelpUsername) ? 4 : 1',
            ),
            'evaluateHtmlOptions' => true,
        ),
        array(
            'class' => 'ClientDataColumn',
            'name' => 'activityLink',
            'value' => '!empty($data->activity) ? CHtml::link("View", Yii::app()->createUrl("am/activity/view", array("id" => $data->id))) : "Pending"',
            'type' => 'raw',
            'header' => 'Activity Stats',
            'showExpression' => '!empty($data->profile->yelpUsername)',
        ),
        array(
            'name' => 'infoLink',
            'value' => '!empty($data->info) ? CHtml::link("View", Yii::app()->createUrl("am/manage/index", array("id" => $data->id))) : "Pending"',
            'type' => 'raw',
            'header' => 'Business Info',
            'class' => 'ClientDataColumn',
            'showExpression' => '!empty($data->profile->yelpUsername)',
        ),
        array(
            'name' => 'photosLink',
            'value' => '!empty($data->photos) ? CHtml::link("View", Yii::app()->createUrl("am/photos/index", array("id" => $data->id))) : "Pending"',
            'type' => 'raw',
            'header' => 'Photos',
            'class' => 'ClientDataColumn',
            'showExpression' => '!empty($data->profile->yelpUsername)',
        ),
        array(
            'name' => 'notes',
            'value' => 'CHtml::link("<span class=\'text\'>" . (!empty($data->notes[0]) ? "Edit Note" : "Add Note") . "</span> <i class=\'icon3-sort\'></i>", "#", array("data-id" => $data->id))',
            'type' => 'raw',
            'htmlOptions' => array(
                'class' => 'expand',
            ),
        )
    ),
));

?>
    </div>

    <div id="ch-sidebar" class="span3">

<?php
    $this->renderPartial('/clients/_sidebar', array('model' => $model));
?>

    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery('input[name="Note[dueAt]"]').data('date-startdate', new Date(Date.now()));
    });
    jQuery(document).on('click', '.expand a', function()
    {
        var $id = jQuery(this).attr('data-id');
        var $el = jQuery('#client-notes-' + $id);
        if ($el.is(':visible'))
        {
            $el.hide();
            jQuery(this).children('.text').html(jQuery(this).data('text'));
        }
        else
        {
            $el.show();
            jQuery(this).data('text', jQuery(this).children('.text').text());
            jQuery(this).children('.text').html('Close');
        }
    });
    jQuery(document).on('click', 'a.updateNote', function()
    {
        var $id = jQuery(this).attr('data-id');
        var $el = jQuery('#client-notes-' + $id);
        $el.find('.note-text')
            .replaceWith(
                jQuery('<textarea name="text" class="input-block-level"></textarea>')
                    .val($el.find('.note-text').text())
           );
        jQuery(this).text('Save').removeClass('btn-inverse updateNote').addClass('btn-success saveNote');
    });
    jQuery(document).on('click', 'a.saveNote', function()
    {
        console.log('test');
        var $id = jQuery(this).attr('data-id');
        var $note = jQuery(this).attr('data-note');
        var $el = jQuery('#client-notes-' + $id);

        jQuery.ajax({
            type: 'post',
            url: '<?php echo $this->createUrl('notes/update') ?>/id/' + $note,
            data: {
                'note': $el.find('textarea[name=text]').val()
            }
        });
        $el.find('.input-block-level')
            .replaceWith(
                jQuery('<span class="note-text"></span>').html($el.find('textarea[name=text]').val())
            );
        jQuery(this).text('Change text').removeClass('btn-success saveNote').addClass('btn-inverse updateNote');
    });
</script>
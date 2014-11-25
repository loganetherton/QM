<?php
/**
 * Notes index view
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('View Notes');
$this->renderPartial('/layouts/tabs/client', array('active' => $archived ? 'archived' : 'notes'));
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php

        if (!$archived) {
        ?>
        <a class="add-note btn btn-large btn-info"><i class="icon-plus icon-white"></i> Add new note</a>
        <div class="note-form" style="display: none; margin-top: 10px;">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'method' => 'post',
                'action' => $this->createUrl('notes/create', array('id' => 0, 'redirect' => 'notes')),
            ));

            $note = new Note;

            echo CHtml::hiddenField('params', CJSON::Encode($_GET));

            echo $form->textArea($note, 'note', array('class' => 'input-block-level', 'placeholder' => 'type your note here...'));

            echo $form->datePickerRow($note, 'dueAt', array('label' => 'Due At:'));?><br /><?php
            echo $form->label($note, 'important', array('label' => 'Mark as important', 'style' => 'display: inline; vertical-align: top'));?>: <?php
            echo $form->checkBox($note, 'important');?> <p class="line-base"></p> <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));

            $this->endWidget();
            ?>
        </div>

        <?php
        }
        $this->widget('ReviewListView', array(
            'id' => 'clientList',
            'dataProvider' => $data,
            'itemView' => '_note',
            'viewData'=>array(
                'formatter' => Yii::app()->getComponent('format'),
            ),
            'headers' => array('Client', 'Created At', 'Due At', ''),
            'afterAjaxUpdate' => '
                function(id, data)
                {
                    jQuery("input[name=\"Note[dueAt]\"]").datepicker({
                        "language":"en",
                        "format":"mm/dd/yyyy",
                        "weekStart":0
                    });
                }',
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
    jQuery('a.add-note').click(function()
    {
        jQuery('.note-form').toggle();
    });
    jQuery(document).ready(function()
    {
        jQuery('input[name="Note[dueAt]"]').data('date-startdate', new Date(Date.now()));
    });
    jQuery(document).on('click', '.expand a', function()
    {
        var $id = jQuery(this).attr('data-id');
        var $el = jQuery('#note-' + $id);
        if ($el.is(':visible'))
        {
            $el.hide();
            jQuery(this).children('.text').html('Expand');
        }
        else
        {
            $el.show();
            jQuery(this).children('.text').html('Close');
        }
    });
    jQuery(document).on('click', 'a.updateNote', function()
    {
        var $id = jQuery(this).attr('data-id');
        var $el = jQuery('#note-' + $id);
        $el.find('.note-text')
            .replaceWith(
                jQuery('<textarea name="text" class="input-block-level"></textarea>')
                    .val($el.find('.note-text').text())
            );
        jQuery(this).text('Save').removeClass('btn-inverse updateNote').addClass('btn-success saveNote');
    });
    jQuery(document).on('click', 'a.saveNote', function()
    {
        var $id = jQuery(this).attr('data-id');
        var $note = jQuery(this).attr('data-note');
        var $el = jQuery('#note-' + $id);

        jQuery.ajax({
            type: 'post',
            url: '<?php echo $this->createUrl('notes/update') ?>/id/' + $id,
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
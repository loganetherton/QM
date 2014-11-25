<?php
/**
 * Notes index view
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('View Notes');

$this->renderPartial('modal_jscript');
if ($type == Note::TYPE_CLIENT) {
    $this->renderPartial('/layouts/tabs/client', array('active' => $archived ? 'archived' : 'notes'));
}
else {
    $this->renderPartial('/layouts/tabs/review', array('active' => $archived ? 'archived_notes' : 'notes'));
}
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">

        <?php if (!$archived): ?>

        <a class="add-note btn btn-large btn-info"><i class="icon-plus icon-white"></i> Add new note</a>
        <div class="note-form" style="display: none; margin-top: 10px;">
            <?php
            $this->renderPartial('form', array('data' => new Note, 'type' => $type));
            ?>
        </div>

        <?php endif; ?>

        <?php
        $this->widget('ReviewListView', array(
            'id' => 'clientList',
            'dataProvider' => $data,
            'itemView' => '_note',
            'viewData'=>array(
                'formatter' => Yii::app()->getComponent('format'),
            ),
            'headers' => $type == Note::TYPE_CLIENT ? array('Client', 'Subject', 'Created At', 'Due At', '') : array('Client', 'Reviewer', 'Subject', 'Created At', 'Due At', ''),
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
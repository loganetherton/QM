<?php
/**
 * Contains JS for Note's modals
 */

// time() returns now's timestamp according to the server, which we add another day too (86400000 msec in one day)
// But bootstrap's datepicker automatically tries to simplifies (read: make hell) things by using UTC
// timestamp, so we add the timezone offset according to the browser in order to normalise that. Result?
// proper tomorrow according to the server
Yii::app()->clientScript->registerScript('datepicker_next_day', "
    var fixDatepickerNext = function()
    {
        jQuery('input[name=\'Note[dueAt]\']').data('date-startdate', new Date(today));
        jQuery('input[name=\'Note[dueAt]\']').datepicker({
            'language':'en',
            'format':'mm/dd/yyyy',
            'weekStart':0
        });
        jQuery('input[name=\"Note[dueAt]\"]').each(function() {
            if (jQuery(this).val().length == 0) {
                jQuery(this).datepicker('update', new Date(tomorrow));
                jQuery(this).datepicker('update');
                jQuery(this).val('');
            }
        });
    }
");

Yii::app()->getClientScript()->registerScript('notes', "
    var today = " . time() . " * 1000 + (new Date()).getTimezoneOffset() * 60000;
    var tomorrow = today + 86400000;

    jQuery('input[name=\'Note[dueAt]\']').data('date-startdate', new Date(today));
    
    jQuery(document).ready(function()
    {
        fixDatepickerNext();
    });
    jQuery('.grid-view, .list-view').each(function(index, item)
    {
        item = jQuery(item);

        var listView = (item.yiiGridView || item.yiiListView).settings[item.attr('id')];
        if (!listView)
            return;

        var prevFunc = listView.afterAjaxUpdate;
        listView.afterAjaxUpdate = function(id, data)
        {
            if (typeof prevFunc != 'undefined')
                prevFunc(id, data);

            data = jQuery(data);
            jQuery('#ch-content').append(data.find('.modal'));
            fixDatepickerNext();
        };
    });
    jQuery(document).on('click', '.add-note a', function(e)
    {
        e.preventDefault();
        var id = jQuery(this).attr('data-id');
        var el = jQuery('#add-note-' + id);
        el.modal('show');
    });
    jQuery(document).on('click', '.notes-view, .notes-view a', function(e)
    {
        e.preventDefault();
        var id = jQuery(this).attr('data-id');
        var el = jQuery('#notes-' + id);
        el.modal('show');
    });
    jQuery(document).on('click', '.note-edit', function(e)
    {
        e.preventDefault();
        jQuery(this).parent().hide();
        jQuery(this).parent().next().show();
    });
    jQuery(document).on('click', '.note-edit-cancel', function(e)
    {
        e.preventDefault();
        jQuery(this).parent().parent().hide();
        jQuery(this).parent().parent().prev().show();
    });

    jQuery(document).on('click', '.note-archive', function(e)
    {
        e.preventDefault();

        jQuery.ajax({
            url: jQuery(this).attr('href')
        });

        jQuery(this).parent().remove();
    });

    jQuery(document).on('click', '.note-important', function(e)
    {
        e.preventDefault();
        jQuery.ajax({
            url: jQuery(this).attr('href')
        });
        jQuery(this).addClass('hide');
        jQuery(this).parent().find('.icon3-warning-sign').removeClass('hide');
        jQuery(this).parent().find('.note-unimportant').removeClass('hide');
    });

    jQuery(document).on('click', '.note-unimportant', function(e)
    {
        e.preventDefault();
        jQuery.ajax({
            url: jQuery(this).attr('href')
        });
        jQuery(this).addClass('hide');
        jQuery(this).parent().find('.icon3-warning-sign').addClass('hide');
        jQuery(this).parent().find('.note-important').removeClass('hide');
    });

    jQuery(document).on('submit', '.note-edit-form > form', function(e)
    {
        e.preventDefault();

        var that = this;

        jQuery.ajax({
            url: jQuery(this).attr('action') + '/ajax/true',
            dataType: 'json',
            type: 'POST',
            data: jQuery(this).serialize(),
            success: function(response)
            {
                console.log(response);
                jQuery(that).parent().parent().removeClass('grid-view-loading');

                var note = jQuery(that).parent().prev();
                note.find('h5 > span').text(response.subject);
                note.find('small > strong').text('due on ' + response.dueAt);
                note.find('p').text(response.note);

                jQuery(that).parent().fadeOut(function()
                {
                    jQuery(that).find('small > strong').text('due on ' + response.dueAt);
                    note.fadeIn();
                });
            }
        });
        jQuery(this).parent().parent().addClass('grid-view-loading');
    });
");
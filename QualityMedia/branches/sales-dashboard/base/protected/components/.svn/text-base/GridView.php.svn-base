<?php
/**
 * Base GridView class, adds push state URL updating
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

Yii::import('bootstrap.widgets.TbGridView');

class GridView extends TbGridView
{
    /**
     * @var string JS function to execute before an AJAX call
     */
    public $beforeAjaxUpdate = '
        function (id, options)
        {
            if (history.pushState) {
                history.pushState({}, window.title, options.url);
            }
        }';
}
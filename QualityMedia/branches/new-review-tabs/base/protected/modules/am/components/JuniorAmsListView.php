<?php
/**
 * Custom changes in CListView to support Junior AM's list.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
Yii::import('zii.widgets.CListView');

class JuniorAmsListView extends CListView
{
    /**
     * @var string $template The template to be used to control the layout of various components in the list view.
     */
    public $template ='{items}<div class="acc_foot"><div class="pagination text-center">{pager}</div></div>';

    public $pagerCssClass = 'jrAmsPagination';
    public $emptyText = 'No Junior Managers linked';
    public $itemsTagName = 'ul';
    public $itemsCssClass = 'list_select';
    public $htmlOptions = array('class' => 'acc_in');
    public $showJrActivitiesButton = true;

    public function init()
    {
        parent::init();

        //Change default pager arrows
        $this->pager = array(
            'header' => false,
            'firstPageLabel' => CHtml::image($this->resourceUrl('images/ico-prev.png', 's3'), ''),
            'prevPageLabel'  => CHtml::image($this->resourceUrl('images/ico-prev-s.png', 's3'), ''),
            'nextPageLabel'  => CHtml::image($this->resourceUrl('images/ico-next-s.png', 's3'), ''),
            'lastPageLabel'  => CHtml::image($this->resourceUrl('images/ico-next.png', 's3'), '')
        );

        //Applies a content wrapper
        $this->applyWrapper();

    }

    /**
     * Creates an absolute URL for the resource (image, css, js, etc).
     * @param string $resource Path to the resource starting in webroot
     * @param string $storage Storage type. Available types are local or s3
     * @return string Url to the resource
     */
    protected function resourceUrl($resource, $storage = 'local')
    {
        switch($storage) {
            case 's3':
                return Yii::app()->getComponent('s3Resource')->getResource($resource);
                break;
            case 'local':
                return Yii::app()->getRequest()->getBaseUrl().'/'.ltrim($resource,'/');
                break;
        }
    }

    /**
     * Applies a content wrapper
     * @return [type] [description]
     */
    protected function applyWrapper()
    {
        $innerContent = '';
       //Add Junior Activities link
        if($this->showJrActivitiesButton) {
            $innerContent .= '<div class="text-center">
                <a target="_blank" href="'.Yii::app()->createUrl('/am/juniorAmActivity').'" class="btn btn-warning btn-block">'.CHtml::image($this->resourceUrl('images/ico-users.png', 's3'), '', array('style' => 'margin: -2px 5px 0 5px')).'Junior Activities</a>
            </div>';
        }

        $wrapperTemplate = '<div class="accordion-inner">
                <div class="acc_head clearfix">
                    <div class="text-center"><h4 >Linked Junior AM\'s</h4></div>
                    '.$innerContent.'
                </div>
                %s
            </div>';
        $this->template = sprintf($wrapperTemplate, $this->template);
    }
}
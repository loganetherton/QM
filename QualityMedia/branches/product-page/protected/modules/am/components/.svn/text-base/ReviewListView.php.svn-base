<?php
/**
 * Custom changes in CListView to support reviews list.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('zii.widgets.CListView');

class ReviewListView extends CListView
{
    /**
     * @var string $template The template to be used to control the layout of various components in the list view.
     */
    public $template="{summary}\n{items}\n{pager}";

    /**
     * @var array|string $pager The configuration for the pager.
     */
    public $pager = array('class'=>'bootstrap.widgets.TbPager');

    /**
     * @var string the CSS class name for the pager container.
     */
    public $pagerCssClass = 'pagination';

    /**
     * @var array $headers Column headers.
     */
    public $headers = array();

    /**
     * Registers the neccessary client scripts
     *
     * @access public
     * @return void
     */
    public function registerClientScript()
    {
        parent::registerClientScript();

        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#pushState#' . $this->getId(), '
            jQuery($.fn.yiiListView.settings["' . $this->getId() . '"].updateSelector).live("click", function()
            {
                if (history.pushState) {
                    history.pushState({}, window.title, jQuery(this).attr("href"));
                }
            });
        ');
    }

    /**
     * Initializes the view.
     */
    public function init()
    {
        // Set pretty page var (instead of Model_page)
        if($this->dataProvider !== null) {
            $this->dataProvider->setPagination(array('pageVar'=>'page'));
        }

        parent::init();
    }

    /**
     * Renders the data item list.
     */
    public function renderItems()
    {
        $data = $this->dataProvider->getData();

        echo '<table class="table table-bordered">'."\n";
        echo '<thead><tr><th>'.implode('</th><th>', $this->headers).'</th></tr></thead>'."\n";
        echo '<tbody>';

        if(($n=count($data))>0) {
            $owner=$this->getOwner();
            $viewFile=$owner->getViewFile($this->itemView);

            foreach($data as $i=>$item) {
                $data=$this->viewData;
                $data['index']=$i;
                $data['data']=$item;
                $data['widget']=$this;
                $owner->renderFile($viewFile,$data);
            }
        }
        else {
            $this->renderEmptyText();
        }

        echo '</tbody>';
        echo '</table>'."\n";
    }

    /**
     * Renders the empty message when there is no data.
     */
    public function renderEmptyText()
    {
        $colspan = count($this->headers);
        echo "<tr><td colspan=\"{$colspan}\">No results found.</td></tr>\n";
    }

    /**
     * @return integer Current list page
     */
    public function getCurrentPage()
    {
        return $this->dataProvider->getPagination()->getCurrentPage() + 1;
    }
}
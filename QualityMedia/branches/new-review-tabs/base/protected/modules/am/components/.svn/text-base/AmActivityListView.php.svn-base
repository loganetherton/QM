<?php
Yii::import('bootstrap.widgets.TbGridView');

class AmActivityListView extends TbGridView {

    /**
     * Render Totals summary block
     */
    public function rendertotals()
    {

        if($this->dataProvider->itemCount < 1) {
            return;
        }

        $criteria = $this->dataProvider->getCriteria();
        $totals = $this->dataProvider->model->getTotals($criteria);

        echo '<div class="well">';
        $output = array();

            foreach($totals as $total => $count) {
                array_push($output, sprintf('%ss: <strong>%s</strong>', $total, $count)) ;
            }

            echo sprintf('<div style="text-align: center">%s</div>', implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $output));

            echo sprintf('<p class="line-base"></p><div class="pull-right">%s: <strong>%s</strong></div><br />', 'Total', $this->dataProvider->totalItemCount);

        echo '</div>';
    }
}
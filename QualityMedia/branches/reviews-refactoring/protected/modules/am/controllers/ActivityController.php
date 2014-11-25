<?php
/**
 * Controller for viewing activity stats of individual clients
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class ActivityController extends AmController
{
    /**
     * Shows the analyitcal info for this business
     *
     * @access public
     * @param int $id
     * @param string $period
     * @param int $show The index of tab to show
     * @return void
     */
    public function actionView($id, $period = '1m', $show = 1)
    {
        $model = YelpAnalytics::model()->findByAttributes(array('businessId' => $id));

        if (!$model) {
            $this->redirect(array('clients/index'));
        }

        $this->render('view', array(
            'id' => $id,
            'model' => $model,
            'arpu' => $model->info['arpu'],
            'period' => $period,
            'show' => $show >= 1 && $show <= 3 ? $show : 1,
            'data' => $model->info[$period == '1y' ? 'one_year' : ($period == '2y' ? 'two_year' : 'one_month')]['data']['stats'],
        ));
    }
}
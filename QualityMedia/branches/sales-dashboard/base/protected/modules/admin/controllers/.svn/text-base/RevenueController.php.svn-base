<?php
/**
 * Revenue controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class RevenueController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new Revenue;

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    public function actionGranularData()
    {
        $model = new Revenue;
        $salesmen = new Salesman;
        $subscriptionStatsModel = new Revenue;
        $revenueStatsModel = new Revenue;
        $selectedSalesmen = null;

        $showDataOptions = array();

        if(isset($_POST['Revenue'])) {
            $model->setAttributes($_POST['Revenue']);

            if(isset($_POST['Revenue']['dateRange']) && !empty($_POST['Revenue']['dateRange'])) {
                $dateRange = explode(" - ", $_POST['Revenue']['dateRange']);
                $dateArray = array("dateFrom" => $dateRange[0]);

                if(isset($dateRange[1])) {
                    $dateArray['dateTo'] = $dateRange[1];
                }
                $model->setAttributes($dateArray);
            }

            if(isset($_POST['Revenue']['showData'])) {
                $showDataOptions = (array) $_POST['Revenue']['showData'];
            }

            if(isset($_POST['Revenue']['salesmenIds'])) {
                $selectedSalesmen = new Salesman;
                $selectedSalesmen->primaryKeysScope((array)$_POST['Revenue']['salesmenIds']);
            }

            if(isset($_POST['Revenue']['salesmenIds'])) {
                $selectedSalesmen = new Salesman;
                $selectedSalesmen->primaryKeysScope((array)$_POST['Revenue']['salesmenIds']);
            }

            if(isset($_POST['Revenue']['sortOrder']) && !empty($_POST['Revenue']['sortOrder'])) {
                $model->sortOrder = $_POST['Revenue']['sortOrder'];
            }
        }

        $this->render('granularData', array(
            'model'=>$model,
            'salesmen' => $salesmen,
            'subscriptionStatsModel' => $subscriptionStatsModel,
            'revenueStatsModel' => $revenueStatsModel,
            'showDataOptions' => $showDataOptions,
            'selectedSalesmen' => $selectedSalesmen
        ));
    }
}
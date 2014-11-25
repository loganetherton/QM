<?php
/**
 * Account Manager Stats controller.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class AmStatsController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new AmStats('search');
        $model->unsetAttributes();

        //handle review type filter
        if(isset($_GET['AmStats']['reviewTypeFilter'])) {
            $model->reviewTypeFilter = $_GET['AmStats']['reviewTypeFilter'];
            //Apply review type filters
            switch($model->reviewTypeFilter) {
                case AmStats::REVIEW_TYPE_FILTER_PRECONTRACT :
                    $model->fullPrecontract();
                break;
                case AmStats::REVIEW_TYPE_FILTER_POSTCONTRACT :
                    $model->postcontract();
                break;
                case AmStats::REVIEW_TYPE_FILTER_ALL :
                default:
                break;
            }
        }

        if(isset($_GET['AmStats']['dateRange']) && $_GET['AmStats']['dateRange']) {
            $model->dateRange = $_GET['AmStats']['dateRange'];
        }

        if(isset($_GET['AmStats'])) {
            $model->setAttributes($_GET['AmStats']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }
}
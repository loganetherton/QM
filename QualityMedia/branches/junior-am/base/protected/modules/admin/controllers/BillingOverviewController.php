<?php
/**
 * Bililng management controller.
 *
 * @author Dawid <dawid@qualitymedia.com>
 */
class BillingOverviewController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new AdminClientSummary('search');

        $this->render('index', array(
            'model'=>$model,
        ));
    }
}
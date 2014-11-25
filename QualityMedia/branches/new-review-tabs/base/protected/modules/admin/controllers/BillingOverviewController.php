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
        $model->unsetAttributes();

        if(isset($_GET['AdminClientSummary'])) {
            $model->setAttributes($_GET['AdminClientSummary']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }
}
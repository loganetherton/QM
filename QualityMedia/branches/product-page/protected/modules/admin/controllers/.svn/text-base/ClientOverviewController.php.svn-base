<?php
/**
 * Client management controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientOverviewController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new AdminClientSummary('search');

        if(isset($_GET['AdminClientSummary'])) {
            $model->setAttributes($_GET['AdminClientSummary']);
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }
}
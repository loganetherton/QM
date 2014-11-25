<?php
/**
 * Dashboard controller.
 * This is the default Admin module controller.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class DashboardController extends AdminController
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * View dashboard action.
     */
    public function actionView()
    {
        $this->redirect(array('revenue/index'));
    }
}
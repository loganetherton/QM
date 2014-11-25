<?php
/**
 * Dashboard controller.
 * This is the default Sales module controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DashboardController extends SalesController
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
        $this->render('view');
    }
}
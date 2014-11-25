<?php
/**
 * Dashboard controller.
 * This is the default Client module controller.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class DashboardController extends ClientController
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
<?php
/**
 * Maintenance controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MaintenanceController extends Controller
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * View action.
     */
    public function actionView()
    {
        echo 'We will be back soon!';
    }
}
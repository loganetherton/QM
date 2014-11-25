<?php
/**
 * Custom changes in abstract Controller class needed by Client module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientController extends Controller
{
    /**
     * @var string $layout The default layout for the controller view.
     */
    public $layout = '/layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users'=>array('@'),
            ),
            array(
                'deny',
                'users'=>array('*'),
            ),
        );
    }
}
<?php
/**
 * Custom changes in abstract Controller class needed by Account Manager module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AmController extends Controller
{
    /**
     * @var string $layout The default layout for the controller view.
     */
    public $layout = 'column2';

    /**
     * @var boolean $layout Junior Manager reviewing mode
     */
    public $jrMode = false;

    /**
     * @var mixed $layout Junior Manager in id reviewing mode
     */
    public $jrModel = null;

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

    /**
     * Sets the Jr Rev reviewing mode.
     * @param object $accountManager Account manager object
     * return void
     */
    public function setJrMode($accountManager)
    {
        $this->jrModel  = $accountManager;
        $this->jrMode   = true;
        $this->layout   = 'column2Jr';
    }
}
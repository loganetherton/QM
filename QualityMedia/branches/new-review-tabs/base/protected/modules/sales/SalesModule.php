<?php
/**
 * Salesmen module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SalesModule extends CWebModule
{
    /**
     * @var string $defaultController The id of the default controller in this module.
     */
    public $defaultController = 'dashboard';

    public function init()
    {
        $this->setImport(array(
            'sales.components.*',
            'sales.models.*',
        ));

        // Set custom home url for admin module
        Yii::app()->setHomeUrl(array('/sales'));

        // Log admin actions into separate log file
        Yii::app()->getComponent('log')->getRoutes()->itemAt('file')->setLogFile('sales.log');
    }
}

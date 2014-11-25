<?php
/**
 * Client module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientModule extends CWebModule
{
    /**
     * @var string $defaultController The id of the default controller in this module.
     */
    public $defaultController = 'dashboard';

    /**
     * Initializes the module.
     */
    public function init()
    {
        $this->setImport(array(
            'client.components.*',
            'client.models.*',
        ));

        // Set custom home url for admin module
        Yii::app()->setHomeUrl(array('/client'));

        // Log admin actions into separate log file
        Yii::app()->getComponent('log')->getRoutes()->itemAt('file')->setLogFile('client.log');
    }
}
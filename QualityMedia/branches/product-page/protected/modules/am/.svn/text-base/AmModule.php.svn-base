<?php
/**
 * Account Manager module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AmModule extends CWebModule
{
    /**
     * @var string $defaultController The id of the default controller in this module.
     */
    public $defaultController = 'review';

    /**
     * Initializes the module.
     */
    public function init()
    {
        $this->setImport(array(
            'am.models.*',
            'am.components.*',
        ));

        // Set custom home url for admin module
        Yii::app()->setHomeUrl(array('/am'));

        // Log admin actions into separate log file
        Yii::app()->getComponent('log')->getRoutes()->itemAt('file')->setLogFile('am.log');
    }
}
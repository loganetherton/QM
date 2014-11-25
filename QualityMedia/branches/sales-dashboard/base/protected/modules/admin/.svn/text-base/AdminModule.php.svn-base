<?php
/**
 * Admin module.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AdminModule extends CWebModule
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
            'admin.components.*',
            'admin.models.*',
        ));

        // Set custom home url for admin module
        Yii::app()->setHomeUrl(array('/admin'));

        // Log admin actions into separate log file
        Yii::app()->getComponent('log')->getRoutes()->itemAt('file')->setLogFile('admin.log');
    }
}

<?php
/**
 * CRM module
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
class CrmModule extends CWebModule
{
    /**
     * @var string $defaultController The id of the default controller in this module.
     */
    public $defaultController = 'contract';

    /**
     * @var string $ccEncryptionKey The encryption key to use while encrypting/decrypting CC info
     */
    public $ccEncryptionKey;

    /**
     * Initialize the module.
     */
    public function init()
    {
        // Import the CRM module models and components
        $this->setImport(array(
            'crm.models.*',
            'crm.components.*',
        ));

        // Set custom home url for crm module
        Yii::app()->setHomeUrl(array('/crm/contract/create'));

        // Log CRM action into their own log file
        Yii::app()->getComponent('log')->getRoutes()->itemAt('file')->setLogFile('crm.log');
    }
}
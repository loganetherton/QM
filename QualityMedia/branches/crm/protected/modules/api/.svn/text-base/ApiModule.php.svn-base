<?php
/**
 * API Module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ApiModule extends CWebModule
{
    /**
     * Initializes the module.
     */
    public function init()
    {
        $this->setImport(array(
            'api.models.*',
            'api.components.*',
        ));

        // Overwrite components for api module
        Yii::app()->setComponents(array(
            'errorHandler'=>array(
                'errorAction'=>'api/error/view',
            ),
        ));
    }
}
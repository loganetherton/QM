<?php
/**
 * Custom changes in YiiBooster Bootstrap component.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('ext.bootstrap.components.Bootstrap');

class BootstrapS3 extends Bootstrap
{
    public function getAssetsUrl()
    {
        return Yii::app()->getComponent('s3Resource')->getResource('bootstrap');
    }
}
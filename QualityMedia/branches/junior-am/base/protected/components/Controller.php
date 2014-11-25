<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string $layout the default layout for the controller view.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array $menu context menu items.
     */
    public $menu = array();

    /**
     * @var array $breadcrumbs the breadcrumbs of the current page.
     */
    public $breadcrumbs = array();

    /**
     * @var array $leftContent optionl left content.
     */
    public $leftContent = null;

    /**
     * @var string $formId Ajax form id - performAjaxValidation method uses this variable.
     */
    protected $formId;

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @param CAction $action the action to be executed.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action)
    {
        // Log all requests made in production environment
        if(getenv('APP_ENV') == 'production') {
            $params = array(
                'get'       => $_GET,
                'post'      => $_POST,
                'cookie'    => $_COOKIE,
                'server'    => $_SERVER,
                'request'   => Yii::app()->getRequest(),
                'user'      => Yii::app()->getUser(),
            );

            Yii::log(serialize($params)."\n", 'request');
        }

        return true;
    }

    /**
     * Set page title.
     * @param string $value the page title.
     */
    public function setPageTitle($value)
    {
        parent::setPageTitle(Yii::app()->name.' - '.$value);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @param string $modelClass Model class name
     */
    public function loadModel($id, $modelClass)
    {
        $model = ActiveRecord::model($modelClass)->findByPk($id);

        if($model === null) {
            throw new CHttpException(404, 'Not found');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param mixed $model Model or array of models to be validated
     * @param string $formId Form id
     */
    protected function performAjaxValidation($model, $formId = null)
    {
        if($formId !== null) {
            $this->formId = $formId;
        }

        if(!isset($this->formId) || $this->formId === null) {
            throw new CHttpException(500, 'Missing form id');
        }

        if(isset($_POST['ajax']) && $_POST['ajax'] === $this->formId) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the tabular AJAX validation.
     * @param mixed $models An array of model instances.
     * @param string $formId Form id
     */
    protected function performTabularAjaxValidation($models, $formId = null)
    {
        if($formId !== null) {
            $this->formId = $formId;
        }

        if(!isset($this->formId) || $this->formId === null) {
            throw new CHttpException(500, 'Missing form id');
        }

        if(isset($_POST['ajax']) && $_POST['ajax'] === $this->formId) {
            echo CActiveForm::validateTabular($models);
            Yii::app()->end();
        }
    }

    /**
     * Creates an absolute URL for the resource (image, css, js, etc).
     * @param string $resource Path to the resource starting in webroot
     * @param string $storage Storage type. Available types are local or s3
     * @return string Url to the resource
     */
    public function resourceUrl($resource, $storage = 'local')
    {
        switch($storage) {
            case 's3':
                return Yii::app()->getComponent('s3Resource')->getResource($resource);
                break;
            case 'local':
                return Yii::app()->getRequest()->getBaseUrl().'/'.ltrim($resource,'/');
                break;
        }
    }
}
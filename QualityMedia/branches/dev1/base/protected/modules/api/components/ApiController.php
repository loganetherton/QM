<?php
/**
 * Custom changes in Controller class needed by API.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ApiController extends Controller
{
    /**
     * @var string $layout The default layout for the controller view.
     * API controllers does not need to use layouts.
     */
    public $layout = false;

    /**
     * Renders OK status JSON.
     * @param mixed $message Success message
     */
    public function renderSuccess($message = '')
    {
        echo CJSON::encode(array('status'=>'success', 'message'=>$message));

        Yii::app()->end();
    }

    /**
     * Renders error JSON.
     * @param mixed $message Error message
     * @param integer $code HTTP status code
     */
    public function renderError($message = '', $code = 500)
    {
        echo CJSON::encode(array('status'=>'error', 'message'=>$message));

        Yii::app()->end();
    }

    /**
     * List all resources.
     */
    public function actionIndex()
    {
        throw new CHttpException(501, 'Not Implemented');
    }

    /**
     * View resource
     * @param integer $id Resource primary key
     */
    public function actionView($id)
    {
        throw new CHttpException(501, 'Not Implemented');
    }

    /**
     * Create resource.
     */
    public function actionCreate()
    {
        throw new CHttpException(501, 'Not Implemented');
    }

    /**
     * Update resource.
     * @param integer $id Resource primary key
     */
    public function actionUpdate($id)
    {
        throw new CHttpException(501, 'Not Implemented');
    }

    /**
     * Delete resource.
     * @param integer $id Resource primary key
     */
    public function actionDelete($id)
    {
        throw new CHttpException(501, 'Not Implemented');
    }
}
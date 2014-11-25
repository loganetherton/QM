<?php
/**
 * Error controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ErrorController extends ApiController
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * This is the action to handle external exceptions.
     * @param mixed $id Just for compatibility with ApiController method
     */
    public function actionView($id = null)
    {
        if($error = Yii::app()->getErrorHandler()->getError()) {
            $this->renderError($error['message'], $error['code']);
        }
    }
}

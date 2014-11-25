<?php
/**
 * Error controller to handle external exceptions.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ErrorController extends Controller
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * This is the action to handle external exceptions.
     */
    public function actionView()
    {
        if($error = Yii::app()->getErrorHandler()->getError()) {
            if(Yii::app()->getRequest()->getIsAjaxRequest()) {
                echo $error['message'];
            }
            else {
                $this->render('view', $error);
            }
        }
    }
}

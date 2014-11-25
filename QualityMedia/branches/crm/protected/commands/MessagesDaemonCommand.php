<?php
/**
 * Post messages daemon.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MessagesDaemonCommand extends DaemonCommand
{
    /**
     * This method is invoked right before an action is to be executed.
     * @param string $action the action name
     * @param array $params the parameters to be passed to the action method.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action, $params)
    {
        $this->pidFile = $action.'.pid';

        return parent::beforeAction($action, $params);
    }

    /**
     * Post public messages.
     */
    public function actionPublic()
    {
        $path = dirname(__FILE__).'/../yiic phantomJs';

        $this->log('[public] Posting public message');
        $result = exec("{$path} postPublicMessage");
        $this->log('[public] '.$result);
    }

    /**
     * Post private messages.
     */
    public function actionPrivate()
    {
        $path = dirname(__FILE__).'/../yiic phantomJs';

        $this->log('[private] Posting private message');
        $result = exec("{$path} postPrivateMessage");
        $this->log('[private] '.$result);
    }
}
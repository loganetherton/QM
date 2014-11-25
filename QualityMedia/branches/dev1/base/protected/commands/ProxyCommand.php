<?php
/**
 * Proxy command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ProxyCommand extends CConsoleCommand
{
    const ATTEMPTS = 3;

    /**
     * Check proxy status.
     */
    public function actionStatus()
    {
        $phantomjs = Yii::app()->getComponent('phantomjs');

        foreach($phantomjs->proxies as $proxy) {
            printf('%-25s', $proxy);

            if(strpos($proxy, ':') !== false) {
                list($proxy, $phantomjs->port) = explode(':', $proxy, 2);
            }

            for($i = 0; $i < self::ATTEMPTS; $i++) {
                echo (int)$this->checkServerStatus($proxy, $phantomjs->port);
            }

            echo "\n";
        }
    }

    /**
     * Check server status.
     * @param string $ip Server IP
     * @param string $port Server port
     * @return boolean Whether server is healthy
     */
    protected function checkServerStatus($ip, $port)
    {
        return (bool)@fsockopen($ip, $port, $errno, $errstr, 2);
    }
}
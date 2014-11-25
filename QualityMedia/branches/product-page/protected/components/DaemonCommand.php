<?php
/**
 * Abstract class for all daemons.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class DaemonCommand extends CConsoleCommand
{
    /**
     * @var string $pidPath PID file path.
     */
    public $pidPath;

    /**
     * @var string $pidFile PID file name.
     */
    public $pidFile;

    /**
     * @var integer $pid Current PID
     */
    protected $pid;

    /**
     * Initializes the command object.
     */
    public function init()
    {
        ini_set('max_execution_time', 0);

        $this->pid = getmypid();

        $this->log(sprintf('%s::init', get_class($this)));

        // Set default pid path if empty
        if($this->pidPath === null) {
            $this->pidPath = Yii::app()->getRuntimePath().'/pid';

            // Make sure PID path exists
            if(!is_dir($this->pidPath)) {
                mkdir($this->pidPath, 0777, true);
            }
        }

        $this->pidPath = rtrim($this->pidPath, '/');

        // Set default pid file if empty
        if($this->pidFile === null) {
            $this->pidFile = 'daemon.pid';
        }
    }

    /**
     * This method is invoked right before an action is to be executed.
     * @param string $action the action name
     * @param array $params the parameters to be passed to the action method.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action, $params)
    {
        $pidFile = $this->getPidFile();

        if(file_exists($pidFile)) {
            $this->log('PID file exists');

            $file = file_get_contents($pidFile);
            $file = CJSON::decode($file);

            if(abs(time() - $file['time']) > 3600) {
                $message = 'Pid file is older than one hour (PID file: %s, Start time: %s, current time: %s)';
                $this->log(sprintf($message, $this->pidFile, date('Y-m-d H:i:s', $file['time']), date('Y-m-d H:i:s')), 'notice');
            }

            return false;
        }

        // If PID file does not exist it mean we are good to go with the script (starting with pid file creation).
        $this->createPidFile();

        $this->log(sprintf('%s::%s', get_class($this), $action));

        return parent::beforeAction($action, $params);
    }

    /**
     * This method is invoked right after an action finishes execution.
     * @param string $action the action name
     * @param array $params the parameters to be passed to the action method.
     * @param integer $exitCode the application exit code returned by the action method.
     * @return integer application exit code (return value is available since version 1.1.11)
     */
    protected function afterAction($action,$params,$exitCode=0)
    {
        parent::afterAction($action, $params, $exitCode);

        $this->deletePidFile();
    }

    /**
     * Get PID file.
     * @return string $path to PID file.
     */
    protected function getPidFile()
    {
        return $this->pidPath.'/'.$this->pidFile;
    }

    /**
     * Creates PID file.
     */
    protected function createPidFile()
    {
        $pid = array(
            'daemon'=> get_class($this),
            'time'  => time(),
        );

        file_put_contents($this->getPidFile(), CJSON::encode($pid));

        $this->log('PID file has been created');
    }

    /**
     * Deletes PID file.
     */
    protected function deletePidFile()
    {
        $pidFile = $this->getPidFile();

        if(file_exists($pidFile)) {
            unlink($pidFile);
        }

        $this->log('PID file has been deleted');
    }

    /**
     * Shortcut for log function.
     * @param string $message Message to be logged
     * @param string $level Log level
     */
    protected function log($message, $level = CLogger::LEVEL_INFO)
    {
        Yii::log(sprintf('[%s] %s', $this->pid, $message), $level, 'reviews-daemon');
    }
}
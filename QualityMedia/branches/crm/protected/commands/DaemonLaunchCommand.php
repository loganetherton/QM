<?php
/**
 * Daemon launch command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.components.DaemonLaunchBehavior');

class DaemonLaunchCommand extends CConsoleCommand
{
    /**
     * @var boolean $trace Trace mode.
     */
    public $trace = false;

    /**
     * @var boolean $traceMemory Trace memory mode.
     */
    public $traceMemory = false;

    /**
     * @var boolean $verbose Verbose mode
     */
    public $verbose = false;

    /**
     * @var boolean $daemonize Whether run the script as daemon.
     */
    public $daemonize = false;

    /**
     * @var string $pd PID directory.
     */
    public $pd;

    /**
     * Launch the daemon.
     * @param array @args Command line parameters for this command.
     */
    public function actionRun($args)
    {
        $daemonOverseer = new DaemonOverseer($this, $args);

        $daemonOverseer->attachBehavior('onDaemonLaunchBehavior', new DaemonLaunchBehavior);
        $daemonOverseer->run();
    }
}
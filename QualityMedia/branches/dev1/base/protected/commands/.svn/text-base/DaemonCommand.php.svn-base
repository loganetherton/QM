<?php
/**
 * Daemon management command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.components.ConsoleFormatter');
Yii::import('application.workflows.*');
Yii::import('application.daemons.*');

class DaemonCommand extends CConsoleCommand
{
    /**
     * @var string $defaultAction The name of the default action.
     */
    public $defaultAction = 'help';

    /**
     * Show status of running daemons.
     */
    public function actionStatus()
    {
        $workflow = new StatusDaemonWorkflow;
        $workflow->execute();
    }

    /**
     * Start the standard configured collection of daemons.
     */
    public function actionStart()
    {
        $workflow = new StartDaemonWorkflow;
        $workflow->execute();
    }

    /**
     * Stop all running daemons.
     */
    public function actionStop()
    {
        $workflow = new StopDaemonWorkflow;
        $workflow->execute();
    }

    /**
     * Stop, then start the standard daemon loadout.
     */
    public function actionRestart()
    {
        $workflow = new RestartDaemonWorkflow;
        $workflow->execute();
    }

    /**
     * Start a specific daemon(s).
     * @param array $args Command line arguments
     */
    public function actionLaunch($args)
    {
        $workflow = new LaunchDaemonWorkflow;
        $workflow->execute($args);
    }

    /**
     * Start daemons in the foreground and print diagnostic information.
     */
    public function actionDebug($args)
    {
        $workflow = new DebugDaemonWorkflow;
        $workflow->execute($args);
    }

    /**
     * Help action.
     */
    public function actionHelp()
    {
        echo "The following actions are available:\n\n" . $this->getHelp();
    }

    /**
     * Provides the command description.
     * @return string The command description.
     */
    public function getHelp()
    {
        $actions = array(
            'status'    => 'Show status of running daemons',
            'start'     => 'Start the standard configured collection of daemons',
            'stop'      => 'Stop all running daemons',
            'restart'   => 'Stop, then start the standard daemon loadout',
            'launch'    => 'Start a specific daemon, or n copies of a specific daemon',
            'debug'     => 'Start daemon in the foreground and print large volumes of diagnostic information to the console',
            'help'      => 'Show this help',
        );

        $help = '';
        foreach($actions as $action => $description) {
            $help .= '**'.$action.'**' . "\n" . $description . "\n\n";
        }

        $consoleFormatter = new ConsoleFormatter;
        return $consoleFormatter->formatString($help);
    }

    /**
     * Executes the command.
     * @param array $args Command line parameters for this command
     * @return integer Application exit code, which is returned by the invoked action. 0 if the action did not return anything.
     */
    public function run($args)
    {
        try {
            return parent::run($args);
        }
        catch(Exception $e) {
            $this->usageError($e->getMessage());
        }
    }
}
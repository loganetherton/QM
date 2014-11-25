<?php
/**
 * Base class for all daemon related workflows.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.futures.ExecFuture');

abstract class DaemonWorkflow extends Workflow
{
    /**
     * @return string PID directory
     */
    protected function getPidDirectory()
    {
        return Yii::app()->getRuntimePath() . '/daemonpid';
    }

    /**
     * @return array List of running daemons.
     */
    protected function loadRunningDaemons()
    {
        $daemons = array();

        $pidDirectory = $this->getPidDirectory();
        $pidFiles = CFileHelper::findFiles($pidDirectory);

        if(!$pidFiles) {
            return $daemons;
        }

        foreach($pidFiles as $pidFile) {
            $pidData = @file_get_contents($pidFile);

            if($pidData === false) {
                throw new CException("Failed to read file `{$pidFile}`");
            }

            $dict = CJSON::decode($pidData);
            if(!is_array($dict)) {
                // Just return a hanging reference, since control code needs to be
                // robust against unusual system states.
                $dict = array();
            }

            $ref = DaemonReference::newFromDictionary($dict);
            $ref->setPIDFile($pidFile);

            $daemons[] = $ref;
        }

        return $daemons;
    }

    /**
     * Launch a daemon.
     * @param string $daemon Daemon class name
     * @param array $argv Daemon arguments
     * @param boolean $debug Whether run the daemon in debug mode
     */
    protected function launchDaemon($daemon, array $argv, $debug)
    {
        if($debug) {
            echo "Launching daemon '{$daemon}' in debug mode (not daemonized)\n";
        }
        else {
            echo "Launching daemon '{$daemon}'\n";
        }

        foreach($argv as $key => $arg) {
            $argv[$key] = escapeshellarg($arg);
        }

        $flags = array();

        if($debug) {
            $flags[] = '--trace';
            $flags[] = '--verbose';
        }
        else {
            $flags[] = '--daemonize';
        }

        $pidDirectory = $this->getPidDirectory();

        $flags[] = sprintf('--pd=%s', $pidDirectory);

        $flags  = implode(' ', $flags);
        $argv   = implode(' ', $argv);

        $command = sprintf('./yiic daemonLaunch run %s %s %s', $daemon, $flags, $argv);
        $cwd = Yii::app()->getBasePath() . '/';

        if($debug) {
            // Don't terminate when the user sends ^C; it will be sent to the subprocess which will terminate normally.
            pcntl_signal(SIGINT, array($this, 'ignoreSignal'));

            echo "\n{$command}\n\n";

            $spec  = array(STDIN, STDOUT, STDERR);
            $pipes = array();

            $proc = @proc_open($command, $spec, $pipes, $cwd);

            if(!is_resource($proc)) {
                throw new CException('Failed to execute proc_open()');
            }

            return proc_close($proc);
        }
        else {
            $future = new ExecFuture('exec %s', $command);
            $future->setCWD($cwd);
            $future->resolvex();
        }
    }

    /**
     * Ignore incoming signal. This method is used in debug mode.
     * @param integer $signo Incoming signal
     * @return void
     */
    public function ignoreSignal($signo)
    {
        return;
    }

    /**
     * Execute start command.
     * @throws CException on error
     */
    protected function executeStartCommand()
    {
        $running = $this->loadRunningDaemons();

        // This may include daemons which were launched but which are no longer
        // running; check that we actually have active daemons before failing.
        foreach($running as $daemon) {
            if($daemon->isRunning()) {
                throw new CException('Unable to start daemons because daemons are already running');
            }
        }

        $daemons = array();

        for($i = 0; $i < Yii::app()->params['taskmasters']; $i++) {
            $daemons[] = array('TaskmasterDaemon', array());
        }

        foreach($daemons as $spec) {
            list($name, $argv) = $spec;
            $this->launchDaemon($name, $argv, $isDebug = false);
        }

        echo "\nDone\n";
    }

    /**
     * Execute stop command.
     * @return integer 0 on success
     * @throws CException on error
     */
    protected function executeStopCommand()
    {
        $runningDaemons = $this->loadRunningDaemons();

        if(empty($runningDaemons)) {
            throw new CException('There are no running daemons');
        }

        $runningDaemons = $allDaemons = Arr::methodPull($runningDaemons, null, 'getPID');

        foreach($runningDaemons as $key => $daemon) {
            $pid  = $daemon->getPID();
            $name = $daemon->getName();

            echo "Stopping daemon '{$name}' ({$pid})...\n";

            if(!$daemon->isRunning()) {
                echo "Daemon is not running\n";
                unset($runningDaemons[$key]);
            }
            else {
                posix_kill($pid, SIGINT);
            }
        }

        $start = time();
        do {
            foreach($runningDaemons as $key => $daemon) {
                $pid = $daemon->getPID();

                if(!$daemon->isRunning()) {
                    echo "Daemon {$pid} exited normally\n";
                    unset($runningDaemons[$key]);
                }
            }

            if(empty($runningDaemons)) {
                break;
            }

            usleep(100000);
        } while(time() < $start + 15);

        foreach($runningDaemons as $key => $daemon) {
            $pid = $daemon->getPID();
            echo "Sending daemon {$pid} a SIGKILL\n";

            posix_kill($pid, SIGKILL);
        }

        foreach($allDaemons as $daemon) {
            if($daemon->getPIDFile()) {
                $pidFile = $daemon->getPIDFile();
                unlink($pidFile);
            }
        }

        return 0;
    }
}
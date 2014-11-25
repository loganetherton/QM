<?php
/**
 * Start a specific daemon.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class LaunchDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('launch');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        $daemonCount = 1;

        if($args) {
            if(is_numeric(reset($args))) {
                $daemonCount = array_shift($args);
            }

            if($daemonCount < 1) {
                throw new CException('You must launch at least one daemon');
            }
        }

        if(!$args) {
            throw new CException('You must specify which daemon to launch');
        }

        $daemonClass = array_shift($args);

        for($i = 0; $i < $daemonCount; $i++) {
            $this->launchDaemon($daemonClass, $args, $isDebug = false);
        }
    }
}
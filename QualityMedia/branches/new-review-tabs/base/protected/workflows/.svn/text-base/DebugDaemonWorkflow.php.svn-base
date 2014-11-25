<?php
/**
 * Debug daemons workflow.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DebugDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('debug');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        if(!$args) {
            echo "You must specify which daemon to debug\n";
            return;
        }

        $daemonClass = array_shift($args);

        $this->launchDaemon($daemonClass, $args, $isDebug = true);
    }
}
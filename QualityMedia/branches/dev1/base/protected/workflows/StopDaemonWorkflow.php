<?php
/**
 * Stop daemons workflow.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class StopDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('stop');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        $this->executeStopCommand();
    }
}
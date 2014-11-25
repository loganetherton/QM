<?php
/**
 * Start daemons workflow.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class StartDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('start');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        $this->executeStartCommand();
    }
}
<?php
/**
 * Restart daemon workflow.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class RestartDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('restart');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        $error = $this->executeStopCommand();

        if($error) {
            return $error;
        }

        $this->executeStartCommand();
    }
}
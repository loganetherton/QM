<?php
/**
 * Status daemons workflow.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class StatusDaemonWorkflow extends DaemonWorkflow
{
    /**
     * Internal class constructor.
     */
    protected function didConstruct()
    {
        $this->setName('status');
    }

    /**
     * Execute workflow.
     * @param array $args Command line arguments
     */
    public function execute($args = array())
    {
        $daemons = $this->loadRunningDaemons();

        if(!$daemons) {
            echo "There are no running daemons\n";
            return;
        }

        printf("%-5s\t%-24s\t%s\n", 'PID', 'Started', 'Daemon');

        foreach($daemons as $daemon) {
            $name = $daemon->getName();

            if(!$daemon->isRunning()) {
                $name = '<DEAD> '.$name;
            }

            $start = $daemon->getStart() ? date('M j Y, g:i:s A', $daemon->getStart()) : null;
            printf("%-5s\t%-24s\t%s\n", $daemon->getPID(), $start, $name);
        }
    }
}
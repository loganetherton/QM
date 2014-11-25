<?php
/**
 * Worker lease.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class WorkerLease
{
    /**
     * Fetch tasks to be executed and lease them (if available).
     * @return array List of tasks to be executed
     */
    public function getAndLeaseTasks()
    {
        $taskModel = new WorkerActiveTask;
        $leaseOwnershipName = $this->getLeaseOwnershipName();

        // UPDATE query with WHERE and ORDER BY clauses is very slow.
        // To avoid this, do SELECT first and then UPDATE.
        // NOTE: This approach may cause race condition.
        $tasks = $taskModel->getTasks($limit = 1);

        if($tasks) {
            $taskModel->leaseTasks($tasks, $leaseOwnershipName);
        }

        return $tasks;
    }

    /**
     * @return string Lease ownership name
     */
    protected function getLeaseOwnershipName()
    {
        static $sequence = 0;

        $parts = array(
            getmypid(),
            time(),
            php_uname('n'),
            ++$sequence,
        );

        return implode(':', $parts);
    }
}
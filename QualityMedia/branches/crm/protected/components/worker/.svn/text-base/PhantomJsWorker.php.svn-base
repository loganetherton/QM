<?php
/**
 * Base class for all phantomjs related workers.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class PhantomJsWorker extends Worker
{
    /**
     * Return the number of seconds this worker needs hold a lease on the task for while it performs work.
     * @return integer Number of seconds this task needs to remain leased for
     */
    public function getRequiredLeaseTime()
    {
        return 1800;    // 30 minutes
    }

    /**
     * Return the number of seconds a task should wait after a failure before retrying.
     * @param object $task Task object. This object is probably
     * useful mostly to examine the failure count if you want to implement staggered retries.
     * @return integer|null Number of seconds to wait between retries,
     * or null for a default retry period (currently 60 seconds).
     */
    public function getWaitBeforeRetry($task)
    {
        // Use required lease time
        return $this->getRequiredLeaseTime();
    }

    /**
     * Create a per-business lock to prevent daemons from loggin each other out.
     * @param string $lockKey Lock key
     * @return boolean True on success
     */
    protected function lockBusiness($lockKey)
    {
        if(!Yii::app()->getComponent('lock')->add($lockKey, '', $this->getRequiredLeaseTime())) {
            // Store mutex in cache if cache does not contain a mutex for this business yet.
            // Otherwise throw an exception and stop task from being executed
            echo "Other daemon is currently processing another task for this business ({$lockKey})";
            exit;
        }

        return true;
    }

    /**
     * Unlock business so other tasks for the businesses can be processed.
     * This usually means that task is done.
     * @param string $lockKey
     * @return boolean If no error happens during deletion
     */
    protected function unlockBusiness($lockKey)
    {
        return Yii::app()->getComponent('lock')->delete($lockKey);
    }
}
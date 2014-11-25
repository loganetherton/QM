<?php
/**
 * Base worker class.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class Worker
{
    /**
     * @var mixed $data Worker data.
     */
    protected $data;

    /**
     * Constructor.
     * @param mixed $data Worker data
     */
    final public function __construct($data)
    {
        $this->data = CJSON::decode($data);
    }

    /**
     * Return the number of seconds this worker needs hold a lease on the task for
     * while it performs work. For most tasks you can leave this at `null`, which
     * will give you a short default lease (currently 60 seconds).
     *
     * For tasks which may take a very long time to complete, you should return
     * an upper bound on the amount of time the task may require.
     *
     * @return integer|null Number of seconds this task needs to remain leased for,
     * or null for a default (currently 60 second) lease.
     */
    public function getRequiredLeaseTime()
    {
        return null;
    }

    /**
     * Return the maximum number of times this task may be retried before it
     * is considered permanently failed. By default, tasks retry indefinitely.
     * You can throw a WorkerPermanentFailureException to cause an immediate permanent failure.
     * @return integer|null Number of times the task will retry before permanent failure.
     * Return `null` to retry indefinitely.
     */
    public function getMaximumRetryCount()
    {
        return null;
    }

    /**
     * Return the number of seconds a task should wait after a failure before retrying.
     * For most tasks you can leave this at `null`,
     * which will give you a short default retry period (currently 60 seconds).
     * @param object $task Task object. This object is probably
     * useful mostly to examine the failure count if you want to implement staggered retries.
     * @return integer|null Number of seconds to wait between retries,
     * or null for a default retry period (currently 60 seconds).
     */
    public function getWaitBeforeRetry($task)
    {
        return null;
    }

    /**
     * Execute task.
     */
    final public function executeTask()
    {
        $this->doWork();
    }

    /**
     * Do the actual work
     */
    abstract protected function doWork();
}
<?php
/**
 * Daemon worker task is locked because a task related to the business is being by other worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsBusinessLockException extends WorkerException
{
    /**
     * @var integer $prorogation Task execution prorogation.
     */
    protected $prorogation = 300;    // 5 minutes
}
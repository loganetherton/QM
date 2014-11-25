<?php
/**
 * Daemon worker short failure exception.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class WorkerShortFailureException extends WorkerException
{
    /**
     * @var integer $prorogation Task execution prorogation.
     */
    protected $prorogation = 600;   // 10 minutes
}
<?php
/**
 * Base exception for worker exceptions.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class WorkerException extends CException
{
    /**
     * @var integer $prorogation Task execution prorogation.
     */
    protected $prorogation = 0;

    /**
     * @return integer Task execution prorogation.
     */
    public function getProrogation()
    {
        return $this->prorogation;
    }
}
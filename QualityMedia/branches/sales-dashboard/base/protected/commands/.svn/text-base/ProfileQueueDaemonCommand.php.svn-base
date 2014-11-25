<?php
/**
 * Profile queue daemon.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.commands.QueueDaemonCommand');

class ProfileQueueDaemonCommand extends QueueDaemonCommand
{
    /**
     * @var string $pidFile PID file name.
     */
    public $pidFile = 'profileQueueDaemon.pid';

    /**
     * @var string $model Model name.
     */
    protected $model = 'ProfileQueue';
}
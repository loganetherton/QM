<?php
/**
 * Attach event handlers to "daemon overseer".
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DaemonLaunchBehavior extends CBehavior
{
    /**
     * @var array $daemons List of running daemons' models.
     */
    protected $daemons = array();

    /**
     * Attach event handlers.
     * @param object $owner Owner class
     */
    public function attach($owner)
    {
        $owner->attachEventHandler('onDaemonLaunch', array($this, 'handleDaemonLaunch'));
        $owner->attachEventHandler('onDaemonLogMessage', array($this, 'handleDaemonLogMessage'));
        $owner->attachEventHandler('onDaemonHeartbeat', array($this, 'handleDaemonHeartBeat'));
        $owner->attachEventHandler('onDaemonExit', array($this, 'handleDaemonExit'));
    }

    /**
     * Handle daemon launch event.
     * @param object $event Event object
     */
    public function handleDaemonLaunch($event)
    {
        $id = $event->params['id'];

        $daemon = new DaemonLog;
        $daemon->setAttributes(array(
            'name'   => $event->params['daemonClass'],
            'host'   => php_uname('n'),
            'pid'    => getmypid(),
            'argv'   => $event->params['argv'],
            'status' => DaemonLog::STATUS_RUNNING,
        ));

        $daemon->save();

        $this->daemons[$id] = $daemon;
    }

    /**
    * Handle daemon log event.
    * @param object $event Event object
     */
    public function handleDaemonLogMessage($event)
    {
        $daemon = $this->getDaemon($event->params['id']);

        $message = $event->params['message'];
        $context = $event->params['context'];

        if(strlen($context) && $context !== $message) {
            $message = "({$context}) {$message}";
        }

        $type = $event->params['type'];

        $daemonLogEvent = new DaemonLogEvent;
        $daemonLogEvent->setAttributes(array(
            'daemonLogId'   => $daemon->id,
            'type'          => $type,
            'message'       => $message,
        ));

        $daemonLogEvent->save();

        switch($type) {
            case 'WAIT':
                $currentStatus = DaemonLog::STATUS_WAITING;
                break;
            default:
                $currentStatus = DaemonLog::STATUS_RUNNING;
                break;
        }

        if($currentStatus !== $daemon->status) {
            $daemon->status = $currentStatus;
            $daemon->save();
        }
    }

    /**
     * Handle heartbeat event.
     * @param object $event Event object
     */
    public function handleDaemonHeartBeat($event)
    {
        $daemon = $this->getDaemon($event->params['id']);

        // Just update the timestamp
        $daemon->save();
    }

    /**
     * Handle daemon exit event.
     * @param object $event Event object
     */
    public function handleDaemonExit($event)
    {
        $id = $event->params['id'];

        $daemon = $this->getDaemon($id);
        $daemon->setAttributes(array(
            'status'=>DaemonLog::STATUS_EXITED,
        ));

        $daemon->save();

        unset($this->daemons[$id]);
    }

    /**
     * Get daemon object.
     * @param string $daemon Daemon id
     * @return object Daemon object
     * @throws CException if daemon does not exist
     */
    protected function getDaemon($id)
    {
        if(isset($this->daemons[$id])) {
            return $this->daemons[$id];
        }

        throw new CException("No such daemon '{$id}'!");
    }
}
<?php
/**
 * Signal helper.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Signal
{
    /**
     * Return a human-readable signal name (like "SIGINT" or "SIGKILL") for a given signal number.
     * @param integer $signo Signal number
     * @return string Human-readable signal name.
     */
    public static function getSignalName($signo)
    {
        // These aren't always defined; try our best to look up the signal name.
        $constantNames = array(
            'SIGHUP',
            'SIGINT',
            'SIGQUIT',
            'SIGILL',
            'SIGTRAP',
            'SIGABRT',
            'SIGIOT',
            'SIGBUS',
            'SIGFPE',
            'SIGUSR1',
            'SIGSEGV',
            'SIGUSR2',
            'SIGPIPE',
            'SIGALRM',
            'SIGTERM',
            'SIGSTKFLT',
            'SIGCLD',
            'SIGCHLD',
            'SIGCONT',
            'SIGTSTP',
            'SIGTTIN',
            'SIGTTOU',
            'SIGURG',
            'SIGXCPU',
            'SIGXFSZ',
            'SIGVTALRM',
            'SIGPROF',
            'SIGWINCH',
            'SIGPOLL',
            'SIGIO',
            'SIGPWR',
            'SIGSYS',
            'SIGBABY',
        );

        $signalNames = array();
        foreach($constantNames as $constant) {
            if(defined($constant)) {
                $signalNames[constant($constant)] = $constant;
            }
        }

        return isset($signalNames[$signo]) ? $signalNames[$signo] : null;
    }
}
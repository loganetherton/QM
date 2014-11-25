<?php
/**
 * Execute system commands in parallel using futures.
 *
 * ExecFuture is a future, which means it runs asynchronously and represents
 * a value which may not exist yet. See @{article:Using Futures} for an
 * explanation of futures. When an ExecFuture resolves, it returns the exit
 * code, stdout and stderr of the process it executed.
 *
 * ExecFuture is the core command execution implementation in libphutil, but is
 * exposed through a number of APIs. See @{article:Command Execution} for more
 * discussion about executing system commands.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
final class ExecFuture extends Future
{
    protected $pipes        = array();
    protected $proc         = null;
    protected $start        = null;
    protected $timeout      = null;
    protected $procStatus   = null;

    protected $stdout       = null;
    protected $stderr       = null;
    protected $stdin        = null;
    protected $closePipe    = false;

    protected $stdoutPos    = 0;
    protected $stderrPos    = 0;
    protected $command      = null;
    protected $env          = null;
    protected $cwd;

    protected $stdoutSizeLimit = PHP_INT_MAX;
    protected $stderrSizeLimit = PHP_INT_MAX;

    protected $killedByTimeout;

    protected $descriptorSpec = array(
        0 => array('pipe', 'r'),  // stdin
        1 => array('pipe', 'w'),  // stdout
        2 => array('pipe', 'w'),  // stderr
    );

    /**
     * Create a new ExecFuture.
     * @param string $command sprintf-style command
     * @param mixed Zero or more additional arguments for sprintf:csprintf
     * @return object ExecFuture for running the specified command
     */
    public function __construct($command)
    {
        $argv = func_get_args();
        $this->command = call_user_func_array('sprintf', $argv);
    }

    /**
     * @return string Raw command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set a maximum size for the stderr read buffer.
     * @param integer $limit Maximum size of the stderr read buffer
     * @return this
     */
    public function setStderrSizeLimit($limit)
    {
        $this->stderrSizeLimit = $limit;

        return $this;
    }

    /**
     * @return int Maximum stderr buffer size, in bytes
     */
    public function getStderrSizeLimit()
    {
        return $this->stderrSizeLimit;
    }

    /**
     * Set a maximum size for the stdout read buffer.
     * The major use of these methods is to use less memory if you are running a command
     * which sometimes produces huge volumes of output that you don't really care about.
     *
     * NOTE: Setting this to 0 means "no buffer", not "unlimited buffer".
     *
     * @param integer $limit Maximum size of the stdout read buffer
     * @return this
     */
    public function setStdoutSizeLimit($limit)
    {
        $this->stdoutSizeLimit = $limit;

        return $this;
    }

    /**
     * @return integer Maximum stdout buffer size, in bytes
     */
    public function getStdoutSizeLimit()
    {
        return $this->stdoutSizeLimit;
    }

    /**
     * Get the process's pid. This only works after execution is initiated, e.g. by a call to start().
     * @return integer Process ID of the executing process
     */
    public function getPID()
    {
        $status = $this->procGetStatus();

        return $status['pid'];
    }

    /**
     * Set the current working directory to use when executing the command.
     * @param string $cwd Directory to set as CWD before executing the command
     * @return this
     */
    public function setCWD($cwd)
    {
        $this->cwd = $cwd;

        return $this;
    }

    /**
     * Set the environment variables to use when executing the command.
     * @param array $env Environment variables to use when executing the command
     * @param boolean $wipeProcessEnv Whether to skip $_ENV
     * @return this
     */
    public function setEnv($env, $wipeProcessEnv = false)
    {
        if($wipeProcessEnv) {
            $this->env = $env;
        }
        else {
            $this->env = $env + $_ENV;
        }

        return $this;
    }

    /**
     * Read and return output from stdout and stderr, if any is available.
     * This method keeps a read cursor on each stream, but the entire streams are
     * still returned when the future resolves. You can call read() again after
     * resolving the future to retrieve only the parts of the streams you did not
     * previously read:
     *
     *   $future = new ExecFuture('...');
     *   // ...
     *   list($stdout) = $future->read(); // Returns output so far
     *   list($stdout) = $future->read(); // Returns new output since first call
     *   // ...
     *   list($stdout) = $future->resolvex(); // Returns ALL output
     *   list($stdout) = $future->read(); // Returns unread output
     *
     * NOTE: If you set a limit with setStdoutSizeLimit or setStderrSizeLimit,
     * this method will not be able to read data past the limit.
     *
     * NOTE: If you call discardBuffers, all the stdout/stderr data
     * will be thrown away and the cursors will be reset.
     *
     * @return array <$stdout, $stderr> pair with new output since the last call to this method
     */
    public function read()
    {
        $stdout = $this->readStdout();
        $result = array($stdout, (string)substr($this->stderr, $this->stderrPos));

        $this->stderrPos = strlen($this->stderr);

        return $result;
    }

    /**
     *
     */
    public function readStdout()
    {
        if($this->start) {
            $this->isReady(); // Sync
        }

        $result = (string)substr($this->stdout, $this->stdoutPos);
        $this->stdoutPos = strlen($this->stdout);

        return $result;
    }

    /**
     * Write data to stdin of the command.
     * @param string $data Data to write.
     * @param bool $keepPipe If true, keep the pipe open for writing. By default, the pipe
     *             will be closed as soon as possible so that commands which
     *             listen for EOF will execute. If you want to keep the pipe open
     *             past the start of command execution, do an empty write with
     *             `$keep_pipe = true` first.
     * @return this
     */
    public function write($data, $keepPipe = false)
    {
        $this->stdin .= $data;
        $this->closePipe = !$keepPipe;

        return $this;
    }

    /**
     * Permanently discard the stdout and stderr buffers and reset the read
     * cursors. This is basically useful only if you are streaming a large amount
     * of data from some process:
     *
     *   $future = new ExecFuture('zcat huge_file.gz');
     *   do {
     *     $done = $future->resolve(0.1);   // Every 100ms,
     *     list($stdout) = $future->read(); // read output...
     *     echo $stdout;                    // send it somewhere...
     *     $future->discardBuffers();       // and then free the buffers.
     *   } while ($done === null);
     *
     * Conceivably you might also need to do this if you're writing a client using
     * ExecFuture and ##netcat##, but you probably should not do that.
     *
     * NOTE: This completely discards the data. It won't be available when the
     * future resolves. This is almost certainly only useful if you need the
     * buffer memory for some reason.
     *
     * @return this
     */
    public function discardBuffers()
    {
        $this->discardStdoutBuffer();

        $this->stderr = '';
        $this->stderrPos = 0;

        return $this;
    }

    /**
     * Discard stdout buffer.
     * @return $this
     */
    public function discardStdoutBuffer()
    {
        $this->stdout = '';
        $this->stdoutPos = 0;

        return $this;
    }

    /**
     * @return bool True if the future was killed for exceeding its time limit
     */
    public function getWasKilledByTimeout()
    {
        return $this->killedByTimeout;
    }

    /**
     * Set a hard limit on execution time. If the command runs longer, it will
     * be killed and the future will resolve with an error code. You can test
     * if a future was killed by a timeout with getWasKilledByTimeout.
     * @param int $seconds Maximum number of seconds this command may execute for
     * @return this
     */
    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Resolve a command you expect to exit with return code 0. Works like
     * @{method:resolve}, but throws if $err is nonempty. Returns only
     * $stdout and $stderr. See also @{function:execx}.
     *
     *   list($stdout, $stderr) = $future->resolvex();
     *
     * @param  float Optional timeout after which resolution will pause and
     *               execution will return to the caller.
     * @return pair  <$stdout, $stderr> pair.
     * @task resolve
     */
    public function resolvex($timeout = null)
    {
        list($err, $stdout, $stderr) = $this->resolve($timeout);

        if($err) {
            $cmd = $this->command;
            $message = "Command failed with error #{$err}!";

            throw new ExecCommandException($message, $cmd, $err, $stdout, $stderr);
        }

        return array($stdout, $stderr);
    }

    /**
     * Resolve the process by abruptly terminating it.
     * @return list List of <err, stdout, stderr> results
     */
    public function resolveKill()
    {
        if(defined('SIGKILL')) {
            $signal = SIGKILL;
        }
        else {
            $signal = 9;
        }

        proc_terminate($this->proc, $signal);

        $this->result = array(128 + $signal, $this->stdout, $this->stderr);

        $this->closeProcess();

        return $this->result;
    }

    /**
     * Provides read sockets to the future core.
     * @return array List of read sockets
     */
    public function getReadSockets()
    {
        list($stdin, $stdout, $stderr) = $this->pipes;

        $sockets = array();

        if(isset($stdout) && !feof($stdout)) {
            $sockets[] = $stdout;
        }

        if(isset($stderr) && !feof($stderr)) {
            $sockets[] = $stderr;
        }

        return $sockets;
    }

    /**
     * Provides write sockets to the future core.
     * @return array List of write sockets
     */
    public function getWriteSockets()
    {
        list($stdin, $stdout, $stderr) = $this->pipes;

        $sockets = array();

        if(isset($stdin) && strlen($this->stdin) && !feof($stdin)) {
            $sockets[] = $stdin;
        }

        return $sockets;
    }

    /**
     * Reads some bytes from a stream, discarding output once a certain amount has been accumulated.
     * @param resource $stream Stream to read from
     * @param integer $limit Maximum number of bytes to return from $stream.
     * If additional bytes are available, they will be read and discarded
     * @param string $description Human-readable description of stream, for exception message
     * @return string The data read from the stream
     */
    protected function readAndDiscard($stream, $limit, $description)
    {
        $output = '';

        do {
            $data = fread($stream, 4096);

            if(false === $data) {
                throw new Exception('Failed to read from ' . $description);
            }

            $readBytes = strlen($data);

            if($readBytes > 0 && $limit > 0) {
                if($readBytes > $limit) {
                    $data = substr($data, 0, $limit);
                }

                $output .= $data;
                $limit -= strlen($data);
            }
        } while($readBytes > 0);

        return $output;
    }

    /**
     * Begin or continue command execution.
     * @return bool True if future has resolved
     */
    public function isReady()
    {
        if(!$this->pipes) {
            if(!$this->start) {
                // We might already have started the timer via initating resolution.
                $this->start = microtime(true);
            }

            $pipes  = array();
            $proc   = @proc_open($this->command, $this->descriptorSpec, $pipes, $this->cwd, $this->env);
            $err    = error_get_last();

            if(!is_resource($proc)) {
                throw new Exception("Failed to proc_open(): {$err}");
            }

            $this->pipes = $pipes;
            $this->proc  = $proc;

            list($stdin, $stdout, $stderr) = $pipes;

            if(DIRECTORY_SEPARATOR == '/') {
                // On Windows, there's no such thing as nonblocking interprocess I/O.
                // Just leave the sockets blocking and hope for the best. Some features will not work.
                if(!stream_set_blocking($stdout, false) || !stream_set_blocking($stderr, false) || !stream_set_blocking($stdin, false)) {
                    $this->__destruct();

                    throw new CException('Failed to set streams nonblocking');
                }
            }

            $this->tryToCloseStdin();

            return false;
        }

        if(!$this->proc) {
            return true;
        }

        list($stdin, $stdout, $stderr) = $this->pipes;

        if(isset($this->stdin) && strlen($this->stdin)) {
            $bytes = fwrite($stdin, $this->stdin);

            if($bytes === false) {
                throw new CException('Unable to write to stdin!');
            }
            elseif($bytes) {
                $this->stdin = substr($this->stdin, $bytes);
            }
        }

        $this->tryToCloseStdin();

        // Read status before reading pipes so that we can never miss data that
        // arrives between our last read and the process exiting.
        $status = $this->procGetStatus();

        $stdoutSizeLimit = $this->getStdoutSizeLimit() - strlen($this->stdout);
        $stderrSizeLimit = $this->getStderrSizeLimit() - strlen($this->stderr);

        $this->stdout .= $this->readAndDiscard($stdout, $stdoutSizeLimit, 'stdout');
        $this->stderr .= $this->readAndDiscard($stderr, $stderrSizeLimit, 'stderr');

        if(!$status['running']) {
            $this->result = array(
                $status['exitcode'],
                $this->stdout,
                $this->stderr,
            );

            $this->closeProcess();

            return true;
        }

        $elapsed = (microtime(true) - $this->start);

        if($this->timeout && ($elapsed >= $this->timeout)) {
            $this->killedByTimeout = true;
            $this->resolveKill();

            return true;
        }
    }

    /**
     * Class destructor.
     */
    public function __destruct()
    {
        if(!$this->proc) {
            return;
        }

        // NOTE: If we try to proc_close() an open process, we hang indefinitely. To
        // avoid this, kill the process explicitly if it's still running.

        $status = $this->procGetStatus();

        if($status['running']) {
            $this->resolveKill();
        }
        else {
            $this->closeProcess();
        }
    }

    /**
     * Close and free resources if necessary.
     */
    protected function closeProcess()
    {
        foreach($this->pipes as $pipe) {
            if(isset($pipe)) {
                @fclose($pipe);
            }
        }

        $this->pipes = array(null, null, null);

        if($this->proc) {
            @proc_close($this->proc);

            $this->proc = null;
        }

        $this->stdin = null;
    }

    /**
     * Execute proc_get_status(), but avoid pitfalls.
     * @return array Process status
     */
    protected function procGetStatus()
    {
        // After the process exits, we only get one chance to read proc_get_status()
        // before it starts returning garbage. Make sure we don't throw away the
        // last good read.
        if($this->procStatus) {
            if(!$this->procStatus['running']) {
                return $this->procStatus;
            }
        }

        $this->procStatus = proc_get_status($this->proc);

        return $this->procStatus;
    }

    /**
     * Try to close stdin, if we're done using it. This keeps us from hanging if
     * the process on the other end of the pipe is waiting for EOF.
     */
    protected function tryToCloseStdin()
    {
        if(!$this->closePipe) {
            // We've been told to keep the pipe open by a call to write(..., true).
            return;
        }

        if(strlen($this->stdin)) {
            // We still have bytes to write.
            return;
        }

        list($stdin) = $this->pipes;

        if(!$stdin) {
            // We've already closed stdin.
            return;
        }

        // There's nothing stopping us from closing stdin, so close it.
        @fclose($stdin);

        $this->pipes[0] = null;
    }

    /**
     * @return integer Default amount of time to wait on stream select for this future
     */
    public function getDefaultWait()
    {
        $wait = parent::getDefaultWait();

        if($this->timeout) {
            if(!$this->start) {
                $this->start = microtime(true);
            }

            $elapsed = (microtime(true) - $this->start);
            $wait = max(0, min($this->timeout - $elapsed, $wait));
        }

        return $wait;
    }
}
<?php
/**
 * PhantomJS scripts runner.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsRunner extends CComponent
{
    /**
     * @var string $bin Path to phantomjs binary.
     */
    public $bin = '/usr/bin/phantomjs';

    /**
     * @var string $scriptPath Path to phantomjs scripts.
     */
    public $scriptPath;

    /**
     * @var boolean $useTimeout Whether use timeout script.
     */
    public $useTimeout = false;

    /**
     * @var boolean $timeout Script timeout.
     */
    public $timeout = 3600;

    /**
     * @var array $proxies Script proxies.
     */
    public $proxies = array();

    /**
     * @var integer $port Proxy port.
     */
    public $port;

    /**
     * @var array $userAgents User agents.
     */
    public $userAgents = array();

    /**
     * @var array $params Script params
     */
    protected $params = array();

    /**
     * Initialize component.
     */
    public function init()
    {
    }

    /**
     * Set script params.
     * @param array $params List of params
     */
    public function setParams($params)
    {
        $this->params = array_map(function($param) {
            return escapeshellarg($param);
        }, $params);

        return $this;
    }

    /**
     * Run phantomJs script.
     * @param
     */
    public function execute($script)
    {
        // Setup proxy (if available)
        $proxy = '';
        if(count($this->proxies)) {
            $proxy = $this->getRandomProxy();

            if($proxy === false) {
                return json_encode(array('error'=>'Invalid proxy'));
            }

            $proxy = " --proxy={$proxy}";

            if($this->port !== null) {
                $proxy .= ":{$this->port}";
            }
        }

        // Set user agent (if available)
        if(count($this->userAgents)) {
            $randomKey = array_rand($this->userAgents);
            $this->params[] = sprintf('"%s"', $this->userAgents[$randomKey]);
        }

        $script = rtrim($this->scriptPath, '/') . '/' . $script;
        $command = "{$this->bin}{$proxy} {$script} " . implode(' ', $this->params);

        if($this->useTimeout) {
            $command = "timeout {$this->timeout} {$command}";
        }

        return exec($command);
    }

    /**
     * Get random proxy.
     * @return string Random proxy IP
     */
    protected function getRandomProxy()
    {
        $port = $this->port !== null ? $this->port : 80;

        for($i = 0; $i < 100; $i++) {
            $randomKey = array_rand($this->proxies);
            $proxy = $this->proxies[$randomKey];

            if($this->checkServerStatus($proxy, $port)) {
                return $proxy;
            }
            else {
                Yii::log('Invalid proxy: '.$proxy, CLogger::LEVEL_WARNING);
            }
        }

        return false;
    }

    /**
     * Check server status.
     * @param string $ip Server IP
     * @param string $port Server port
     * @return boolean Whether server is healthy
     */
    protected function checkServerStatus($ip, $port)
    {
        return (bool)@fsockopen($ip, $port, $errno, $errstr, 2);
    }
}
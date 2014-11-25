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
        $this->params = $params;

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
            $randomKey = array_rand($this->proxies);
            $proxy = " --proxy={$this->proxies[$randomKey]}";

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

        return exec($command);
    }
}
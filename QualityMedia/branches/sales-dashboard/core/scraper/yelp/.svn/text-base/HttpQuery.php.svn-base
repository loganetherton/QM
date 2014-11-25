<?php

require __DIR__ . '/ips.php';

class HttpQuery
{
    private $proxyUrl = 'http://74.114.150.156/p/p.php?url=';
    private $params = null;
    private $proxy = null;

    function __construct($params = null)
    {
        $this->params = $params;
    }

    public function useProxy()
    {
        $this->proxy = true;
        return $this;
    }

    public function query()
    {
        if ($this->proxy) {
            $url = $this->proxyUrl . urlencode( $this->params->generateUrl() );
        } else {
            $url = $this->params->generateUrl();
        }

        $ch = curl_init();
        $header = array(
            CURLOPT_USERAGENT      => 'Mozilla/5.0',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPGET        => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_URL            => $url,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
        );

        curl_setopt_array($ch, $header);

        $body = curl_exec($ch);
        curl_close($ch);

        return $body;
    }
}
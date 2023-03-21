<?php

namespace addons\weburlpush\library\push;

use GuzzleHttp\Client;

abstract class Driver
{
    protected $name;
    protected $config = [];
    /** @var  Client Http 客户端 */
    protected $httpClient;
    protected $clientConfig = [];

    public function __construct($name, $config)
    {
        $this->name   = $name;
        $this->config = $config;

    }

    /**
     * 获取http客户端实例
     * @return Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->clientConfig);
        }
        return $this->httpClient;
    }

    abstract public function push($urls);
}

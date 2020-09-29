<?php


namespace Rebuild\Server;


class ServerFactory
{
    /**
     * @var array
     */
    protected $serverConfig = [];
    /**
     * @var Server
     */
    protected $server;

    public function configure(array $config)
    {
        $this->serverConfig = $config;
        $this->getServer()->init($this->serverConfig);
    }

    public function getServer(): Server
    {
        if (!isset($this->server)) {
            $this->server = new Server();
        }
        return $this->server;
    }
}
<?php


namespace Rebuild\Server;


use Swoole\Coroutine\Server as SwooleCoServer;
use Swoole\Server as SwooleServer;
use Swoole\Http\Server as SwooleHttpServer;

class Server implements ServerInterface
{

    /**
     * @var SwooleHttpServer
     */
    protected $server;

    public function __construct()
    {
    }

    public function init(array $config): ServerInterface
    {
        foreach ($config['servers'] as $server) {
            $this->server = new SwooleHttpServer($server['host'], $server['port'], $server['type'], $server['sock_type']);
            $this->registerSwooleEvents($server['callbacks']);
            break;
        }
        return $this;
    }

    public function start()
    {
        $this->getServer()->start();
    }

    /**
     * @inheritDoc
     */
    public function getServer()
    {
        return $this->server;
    }

    private function registerSwooleEvents(array $callbacks)
    {
        foreach ($callbacks as $swooleEvent => $callback) {
            [$class, $method] = $callback; // PHP7的写法，可以将数组展开成两个变量，相当于list
            $instance = new $class();
            $this->server->on($swooleEvent, [$instance, $method]);
        }
    }
}
<?php

namespace Rebuild\Config;

use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public function __invoke()
    {
        $basePath = BASE_PATH . '/config/';
        $configFile = $this->readConfig($basePath . 'config.php');
        $autoloadConfig = $this->readPath([$basePath. '/autoload']);
        //合并返回
        $configs = array_merge_recursive($configFile, $autoloadConfig);
        return new Config($configs);
    }

    private function readConfig(string $string): array
    {
        $config = require $string;
        if (! is_array($config)) {
            return [];
        }
        return $config;
    }

    private function readPath(array $dirs): array
    {
        $config = [];
        $finder = new Finder();
        $finder->files()->in($dirs)->name('*.php');
        foreach ($finder as $findInfo) {
            $key = $findInfo->getBasename('.php');
            $value = require $findInfo->getRealPath();
            $config[$key] = $value;
        }
        return $config;
    }
}
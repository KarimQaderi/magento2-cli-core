<?php

namespace M2\CliCore\App\Helper;

use M2\CliCore\App\Traits\Singleton;

class Path
{
    use Singleton;

    public function dir($path = '')
    {
        return __DIR__ . '/../../' . $path;
    }

    public function cli($path = '')
    {
        return BS . '/' . $path;
    }

    protected function log($path)
    {
        return $this->dir('log/' . $path . '.txt');
    }

    public function logCommand()
    {
        return $this->log('command');
    }

    public function magento($path = '')
    {
        return Config::getInstance()->askDefault('dir') . $path;
    }
}

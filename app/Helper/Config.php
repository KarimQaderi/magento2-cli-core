<?php

namespace M2\CliCore\App\Helper;

use M2\CliCore\App\Traits\Singleton;

/**
 * @property Path path
 */
class Config
{
    use Singleton;

    public function __construct()
    {
        $this->path = Path::getInstance();
    }

    protected function getConfig($name)
    {
        $config = require $this->path->dir('config/' . $name . '.php');

        return $config;
    }

    protected function m2Config($key = null)
    {
        $pathMagento = $this->path->cli('config/app.php');

        $config = require $pathMagento;

        if (isset($config[$key])) {
            return $config[$key];
        }

        return [];
    }

    public function askDefault($key = null)
    {
        $config = $this->m2Config('askDefault');

        if (empty($key)) {
            return $config;
        }

        if (isset($config[$key])) {
            return $config[$key];
        }

        return '';
    }

    public function command()
    {
        return $this->m2Config('command');
    }

    public function commandDefault()
    {
        return $this->getConfig('command');
    }

    public function commandAll()
    {
        return array_merge($this->command(), $this->commandDefault());
    }
}

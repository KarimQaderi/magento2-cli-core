<?php

namespace M2\CliCore\App\Traits;

trait Singleton
{
    public static $instance ;

    public static function getInstance()
    {
        if (!empty(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static(...func_get_args());
    }
}

<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

use Inphinit\App;
use Inphinit\Routing\Router;

class RouteGroup extends Router
{
    private $initiate = true;

    public static function instance()
    {
        $class = get_called_class();
        return new $class;
    }

    public function domain($domain = null)
    {
        App::on('init', array($this, 'run'));
    }

    public function domain($domain = null)
    {
        if (empty($domain)) {
            Exception::raise('domain is not defined', 2);
        }

        return $this;
    }

    public function path($path)
    {
        if (empty($path)) {
            Exception::raise('path is not defined', 2);
        }

        return $this;
    }

    public static function call(\Closure $call)
    {
        return $this;
    }

    public static function run()
    {
        if ($this->initiate) {
            return false;
        }

        $this->initiate = true;
    }
}

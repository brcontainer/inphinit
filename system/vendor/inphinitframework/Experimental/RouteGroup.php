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
    private $ready = true;

    public static function instance()
    {
        $class = get_called_class();
        return new $class;
    }

    public function __construct()
    {
        App::on('init', array($this, 'prepare'));
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

    public static function prepare()
    {
        if ($this->ready) {
            return false;
        }

        $this->ready = true;
    }
}

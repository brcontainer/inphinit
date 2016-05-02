<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

use App;
use Route;

class QuickRoute
{
    private $prefix = '';
    private $methods;
    private $controller;
    private $format;
    private $ready = false;

    const BOTH = 1;
    const SLASH = 2;
    const NOSLASH = 3;

    public static function quick($namecontroller, $prefixroute = null, $slash = null)
    {
        $run = new QuickRoute($namecontroller, $prefixroute, $slash);
        $run->prepare();
        $run = null;
    }

    public function __construct($namecontroller, $prefixroute = null, $slash = null)
    {
        $controller = '\\Controller\\' . strtr($namecontroller, '.', '\\');
        $run = new $controller;

        $this->format = $slash > 0 && $slash < 3 ? $slash : QuickRoute::BOTH;
        $this->methods = get_class_methods($controller);

        if (is_string($prefixroute)) {
            $this->prefix = rtrim($prefixroute, '/');
        }

        App::on('init', array($this, 'prepare'));
    }

    public function prepare()
    {
        if ($this->ready === false) {
            return null;
        }

        $this->ready = true;

        $format     = $this->format;
        $prefix     = $this->prefix;
        $methods    = $this->methods;
        $controller = $this->controller;

        foreach ($methods as $value) {
            if ($format === self::BOTH || $format === self::SLASH) {
                Route::create('ANY', $prefix . '/' . $value . '/', $controller . ':' . $value);
            }

            if ($format === self::BOTH || $format === self::NOSLASH) {
                Route::create('ANY', $prefix . '/' . $value, $controller . ':' . $value);
            }
        }

        $controller = $methods = null;
    }

    public function allow(array $methods)
    {
        $this->methods = array_diff($this->methods, $methods);
    }

    public function disallow(array $methods)
    {
        $this->methods = array_diff($this->methods, $methods);
    }
}

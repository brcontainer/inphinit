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

class QuickRoute extends Router
{
    private $classMethods = array();
    private $controller;
    private $format;
    private $ready = false;

    const BOTH = 1;
    const SLASH = 2;
    const NOSLASH = 3;

    public static function instance($namecontroller)
    {
        return new self($namecontroller);
    }

    public function __construct($namecontroller)
    {
        $this->format = QuickRoute::BOTH;

        $controller = self::$prefixNS . strtr($namecontroller, '.', '\\');
        $fc = '\\Controller\\' . $controller;

        if (class_exists($fc) === false) {
            Exception::raise('Invalid class ' . $fc);
        }

        $this->classMethods = get_class_methods($fc);
        $this->controller   = $controller;

        App::on('init', array($this, 'prepare'));

        return $this;
    }

    public function allow(array $classMethods)
    {
        $this->classMethods = array_diff($this->classMethods, $classMethods);

        return $this;
    }

    public function disallow(array $classMethods)
    {
        $this->classMethods = array_diff($this->classMethods, $classMethods);

        return $this;
    }

    public function canonical($slash = null)
    {
        switch ($slash) {
            case self::BOTH:
            case self::SLASH:
            case self::NOSLASH:
                $this->format = $slash;
            break;
        }

        return $this;
    }

    public function prepare()
    {
        if ($this->ready === false) {
            return null;
        }

        $this->ready = true;

        $format       = $this->format;
        $controller   = $this->controller;
        $classMethods = $this->classMethods;

        foreach ($classMethods as $value) {
            if ($format === self::BOTH || $format === self::SLASH) {
                Route::set('ANY', '/' . $value . '/', $controller . ':' . $value);
            }

            if ($format === self::BOTH || $format === self::NOSLASH) {
                Route::set('ANY', '/' . $value, $controller . ':' . $value);
            }
        }

        $controller = $classMethods = null;
    }
}

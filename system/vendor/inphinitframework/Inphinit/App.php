<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Inphinit;

use Inphinit\Routing\Route;

class App
{
    private static $events = array();
    private static $configs = array();
    private static $initiate = false;
    private static $detectError = false;

    public static function env($key, $value = null)
    {
        if (is_string($value) || is_bool($value) || is_numeric($value)) {
            self::$configs[$key] = $value;
        } elseif ($value === null && isset(self::$configs[$key])) {
            return self::$configs[$key];
        }
    }

    public static function config($path)
    {
        $data = \UtilsSandboxLoader('application/Config/' . strtr($path, '.', '/') . '.php');

        if (empty($data) === false && is_array($data)) {
            foreach ($data as $key => $value) {
                self::env($key, $value);
            }
        }

        $data = null;
    }

    public static function trigger($event, array $args = array())
    {
        if (empty(self::$events[$event])) {
            return false;
        }

        $listen = self::$events[$event];

        if ($event === 'error') {
            self::$detectError = true;
        }

        foreach ($listen as $value) {
            call_user_func_array($value[0], empty($args) ? $value[1] : $args);
        }

        $listen = null;
    }

    public static function hasError()
    {
        return self::$detectError;
    }

    public static function on($name, $callback, array $defaultArgs = array())
    {
        if (is_string($name) === false || is_callable($callback) === false) {
            return false;
        }

        if (isset(self::$events[$name]) === false) {
            self::$events[$name] = array();
        }

        self::$events[$name][] = array($callback, $defaultArgs);

        return true;
    }

    public static function off($name, $callback = null)
    {
        if (empty(self::$events[$name])) {
            return false;
        } elseif ($callback === null) {
            self::$events[$name] = array();
            return true;
        }

        $index = array_search($callback, self::$events);

        if ($index !== false) {
            self::$events[$event][$index] = null;
            unset(self::$events[$event][$index]);
            return true;
        }

        return false;
    }

    public static function buffer($callback = null, $chunksize = 0, $flags = PHP_OUTPUT_HANDLER_STDFLAGS)
    {
        if (ob_get_level() !== 0) {
            ob_end_clean();
        }

        ob_start($callback, $chunksize, $flags);
    }

    public static function stop($code, $msg = null)
    {
        Response::status($code, true);
        self::trigger('changestatus', array($code, $msg));
        self::trigger('finish');
        exit;
    }

    public static function exec()
    {
        if (self::$initiate) {
            return null;
        }

        self::trigger('init');

        self::$initiate = true;

        if (self::env('maintenance') === true) {
            self::stop(503);
        }

        self::trigger('changestatus', array(\UtilsStatusCode(), null));

        $route = Route::get();

        if ($route) {
            $mainController = $route['controller'];
            $parsed = explode(':', $mainController, 2);

            $mainController = '\\Controller\\' . strtr($parsed[0], '.', '\\');
            $action = $parsed[1];

            $run = new $mainController;

            if (empty($route['args'])) {
                $run->$action();
            } else {
                call_user_func_array(array($run, $action), $route['args']);
            }

            $run = null;
        } else {
            App::stop(404, 'Invalid route');
        }

        if (class_exists('\\Inphinit\\Response', false)) {
            Response::dispatchHeaders();
        }

        if (class_exists('\\Inphinit\\View', false)) {
            View::dispatch();
        }

        self::trigger('ready');
        self::trigger('finish');
    }
}

<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Inphinit;

class App
{
    private static $cpath;
    private static $events = array();
    private static $configs = array();
    private static $initiate = false;
    private static $preventDuplicateError = '';

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

    public static function hasError()
    {
        return self::$preventDuplicateError !== '';
    }

    public static function triggerError($type, $message, $file = 'Unknown', $line = 0, $details = null)
    {
        if (class_exists('\\Inphinit\\View', false) && (E_ERROR === $type || E_PARSE === $type)) {
            View::forceRender();
        }

        $str  = '?' . $file . ':' . $line . '?';

        if (empty(self::$events['error']) === false && is_string($message) &&
            strpos(self::$preventDuplicateError, $str) === false)
        {
            self::$preventDuplicateError .= $str;
            self::trigger('error', array($type, $message, $file, $line, $details));
        }

        return false;
    }

    public static function trigger($event, array $args = array())
    {
        if (empty(self::$events[$event])) {
            return false;
        }

        $listen = self::$events[$event];

        foreach ($listen as $value) {
            call_user_func_array($value[0], empty($args) ? $value[1] : $args);
        }

        $listen = null;
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

    public static function prepare()
    {
        self::config('config');

        error_reporting(E_ALL|E_STRICT);
        ini_set('display_errors', self::env('developer') === true ? '1' : '0');

        set_error_handler(array('\\Inphinit\\App', 'triggerError'), E_ALL|E_STRICT);
    }

    public static function exec()
    {
        if (self::$initiate) {
            return null;
        }

        self::trigger('init');

        self::$initiate = true;

        if (self::env('maintenance') === true) {
            Response::status(503);
            self::trigger('maintenance');
        } else {
            $mainController = Route::get();

            if ($mainController) {
                $parsed = explode(':', $mainController, 2);

                $mainController = 'Controller\\' . strtr($parsed[0], '.', '\\');
                $action = $parsed[1];

                $run = new $mainController;
                $run->$action();
                $run = null;
            }
        }

        if (class_exists('\\Inphinit\\Response', false)) {
            Response::dispatchHeaders();
        }

        if (class_exists('\\Inphinit\\View', false)) {
            View::dispatch();
        }

        self::trigger('ready');

        $caller = null;

        self::trigger('beforefinish');
        self::trigger('finish');
    }
}

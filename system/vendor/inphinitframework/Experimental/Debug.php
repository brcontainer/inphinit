<?php
/*
 * Experimental
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

use Inphinit\App;
use Inphinit\View;

class Debug
{
    private static $initiate = false;
    private static $views = array();
    private static $displayErrors;

    private static function register()
    {
        if (self::$initiate) {
            return null;
        }

        self::$initiate = true;

        self::$displayErrors = ini_get('display_errors');

        ini_set('display_errors', '0');

        App::on('error',     array( '\\Experimental\\Debug', 'error' ));
        App::on('terminate', array( '\\Experimental\\Debug', 'performance' ));
        App::on('terminate', array( '\\Experimental\\Debug', 'classes' ));
    }

    public static function unregister()
    {
        if (self::$initiate === false) {
            return false;
        }

        App::off('error',     array( '\\Experimental\\Debug', 'error' ));
        App::off('terminate', array( '\\Experimental\\Debug', 'performance' ));
        App::off('terminate', array( '\\Experimental\\Debug', 'classes' ));

        ini_set('display_errors', self::$displayErrors);

        self::$initiate = false;
    }

    public static function capture($type, $view)
    {
        if ($view !== null && View::exists($view) === false) {
            return false;
        }

        switch ($type) {
            case 'error':
            case 'classes':
            case 'performance':
                self::$views[$type] = $view;
            break;
            default:
                return false;
        }

        self::register();
        return true;
    }

    public static function error($type, $message, $file, $line)
    {
        if (empty(self::$views['error'])) {
            return null;
        }

        $match = array();
        $oFile = $file;

        if (preg_match('#(.*?)\((\d+)\) : eval\(\)\'d code$#', trim($file), $match)) {
            $oFile = $match[1] . ' : eval():' . $line;
            $file  = $match[1];
            $line  = $match[2];
        }

        View::render(self::$views['error'], array(
            'message' => $message,
            'type'    => $type,
            'file'    => $oFile,
            'line'    => $line,
            'source'  => $line > -1 ? self::source($file, $line) : null
        ));
    }

    public static function performance()
    {
        if (empty(self::$views['performance'])) {
            return null;
        }

        View::render(self::$views['performance'], array(
            'usage' => memory_get_usage() / 1024,
            'peak'  => memory_get_peak_usage() / 1024,
            'real'  => memory_get_peak_usage(true) / 1024,
            'time'  => microtime() - INIT_APP
        ));
    }

    public static function classes()
    {
        if (empty(self::$views['classes'])) {
            return null;
        }

        $objs = array();
        $listClasses = get_declared_classes();

        foreach ($listClasses as $value) {
            $value = ltrim($value, '\\');
            $cname = new \ReflectionClass($value);

            if (false === $cname->isInternal()) {
                $objs[$value] = $cname->getDefaultProperties();
            }

            $cname = null;
        }

        $listClasses = null;

        $objs = array( 'classes' => $objs );

        View::render(self::$views['classes'], $objs);
        $objs = null;
    }

    public static function source($source, $line)
    {
        if ($line <= 0 || is_file($source) === false) {
            return null;
        } elseif ($line >= 5) {
            $init = $line - 5;
            $end  = $line + 5;
            $breakpoint = 5;
        } else {
            $init = 0;
            $end  = 5;
            $breakpoint = $line;
        }

        return array(
            'breakpoint' => $breakpoint,
            'preview' => explode(EOL, File::portion($source, $init, $end, true))
        );
    }

    public static function caller($level = 0)
    {
        $trace = debug_backtrace(0);

        if (empty($trace[$level])) {
            return false;
        }

        $file  = $trace[$level]['file'];
        $line  = $trace[$level]['line'];
        $trace = null;

        return array(
            'file' => $file,
            'line' => $line
        );
    }
}

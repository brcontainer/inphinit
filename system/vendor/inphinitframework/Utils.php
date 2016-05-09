<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

use Inphinit\App;

function UtilsCaseSensitivePath($path)
{
    return $path === strtr(realpath($path), '\\', '/');
}

function UtilsSandboxLoader($utilsSandBoxPath, array $utilsSandBoxData = array())
{
    if (empty($utilsSandBoxData) === false) {
        extract($utilsSandBoxData, EXTR_SKIP);
        $utilsSandBoxData = null;
    }

    return include INPHINIT_PATH . $utilsSandBoxPath;
}

function UtilsShutDown()
{
    $e = error_get_last();

    if ($e !== null) {
        UtilsError($e['type'], $e['message'], $e['file'], $e['line'], null);
        $e = null;
    }

    App::trigger('terminate');
}

function UtilsStatusCode($code = null)
{
    static $currentStatus;

    if ($code === null) {
        if ($currentStatus !== null) {
            return $currentStatus;
        }

        $currentStatus = 200;

        if (empty($_SERVER['PHP_SELF']) === false &&
            preg_match('#/RESERVED\.INPHINIT\-(\d{3})\.html$#', $_SERVER['PHP_SELF'], $match) > 0)
        {
            $currentStatus = (int) $match[1];
        }
    } elseif (is_int($code) && headers_sent() === false) {
        header('X-PHP-Response-Code: ' . $code, true, $code);
        $currentStatus = $code;
    }

    return $currentStatus;
}

function UtilsPath()
{
    static $pathInfo;

    if ($pathInfo !== null) {
        return $pathInfo;
    }

    $sname  = $_SERVER['SCRIPT_NAME'];
    $reqUri = empty($_SERVER['REQUEST_URI']) ? null :
                preg_replace('#\?(.*)$#', '', $_SERVER['REQUEST_URI']);

    $pathInfo = substr(urldecode($reqUri), strlen(rtrim(dirname($sname), '/')) + 1);
    $pathInfo = '/' . ($pathInfo === false ? '' : $pathInfo);
    return $pathInfo;
}

function UtilsAutoload()
{
    static $initiate;

    if ($initiate) {
        return null;
    }

    $initiate = true;

    spl_autoload_register(function($classname)
    {
        static $prefixes;

        if (isset($prefixes) === false) {
            $path = INPHINIT_PATH . 'boot/namespaces.php';
            $prefixes = is_file($path) ? include $path : array();
        }

        $classname = ltrim($classname, '\\');
        $cp = array();

        if (is_array($prefixes) === false) {
            return NULL;
        }

        $delimiter = false;
        $isfile = false;
        $base = false;

        if (empty($prefixes) === false) {
            if (isset($prefixes[$classname]) &&
                preg_match('#\.[a-z0-9]+$#', $prefixes[$classname]) !== 0)
            {
                $isfile = true;
                $base = $prefixes[$classname];
            } else {
                foreach ($prefixes as $key => $value) {
                    if (stripos($classname, $key) === 0) {
                        $delimiter = substr($key, -1);
                        $base = trim($value, '/') . '/';
                        break;
                    }
                }
            }
        }

        if ($base === false) {
            return NULL;
        }

        $path = INPHINIT_PATH;

        if ($delimiter !== false) {
            $classname = substr($classname, strpos($classname, $delimiter) + 1);
            $base .= str_replace($delimiter, '/', $classname);
        }

        $files = $isfile ? array( $path . $base ) :
                    array( $path . $base . '.php', $path . $base . '.hh' );

        $files = array_filter($files, 'is_file');

        if (isset($files[0]) && UtilsCaseSensitivePath($files[0])) {
            include_once $files[0];
        }
    });
}

function UtilsError($type, $message, $file, $line, $details)
{
    static $preventDuplicate;

    if (class_exists('\\Inphinit\\View', false) && (E_ERROR === $type || E_PARSE === $type)) {
        Inphinit\View::forceRender();
    }

    $str  = '?' . $file . ':' . $line . '?';

    if (is_string($message)) {
        if ($preventDuplicate === null && strpos($preventDuplicate, $str) === false) {
            $preventDuplicate .= $str;
            App::trigger('error', array($type, $message, $file, $line, $details));
        }
    }

    return false;
}

register_shutdown_function('UtilsShutDown');
set_error_handler('UtilsError', E_ALL|E_STRICT);

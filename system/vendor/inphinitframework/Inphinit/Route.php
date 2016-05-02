<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Inphinit;

class Route
{
    private static $statusRoutes = array();
    private static $httpRoutes = array();
    private static $noRoutes;
    private static $matched;
    private static $cController;
    private static $cVerb;
    private static $prefixNS = '';

    public static function ns($ns)
    {
        if (is_string($ns) && preg_match('#[^a-z0-9\-_\\]#', $ns) === false) {
            $prefixNS = rtrim($ns) . '\\';
        }
    }

    public static function invalid($action)
    {
        if ($action !== null || is_string($action)) {
            self::$noRoutes = $action;
        }
    }

    public static function status($code, $action)
    {
        if (is_array($code)) {
            if (is_array($code)) {
                $method = array_filter($code, 'is_numeric');

                foreach ($code as $value) {
                    self::status($value, $action);
                }
            }
        } elseif (is_numeric($code) && (
            $action !== null || is_string($action)
        )) {
            self::$statusRoutes[$code] = $action;
        }
    }

    public static function set($method, $path, $action)
    {
        if (is_array($method)) {
            if (is_array($method)) {
                $method = array_filter($method, 'ctype_alpha');

                foreach ($method as $value) {
                    self::set($value, $path, $action);
                }
            }
        } elseif (ctype_alpha($method) && is_string($path) && (
            $action !== null || is_string($action)
        )) {
            self::$httpRoutes[strtoupper(trim($method)) . ' ' . $path] = $action;
        }
    }

    public static function matches()
    {
        return self::$matched;
    }

    private static function find($httpMethod, $findRoute, $pathinfo)
    {
        $match = explode(' re:', $findRoute, 2);

        if ($match[0] !== 'ANY' && $match[0] !== $httpMethod) {
            return false;
        }

        if (preg_match($match[1], $pathinfo, $match) > 0) {
            self::$matched = $match;
            return true;
        }

        return false;
    }

    public static function get()
    {
        if (self::$cController !== null) {
            return self::$cController;
        }

        $func = false;
        $verb = false;

        $routes = array_filter(self::$statusRoutes);

        if (empty($routes) === false) {
            $status = \UtilsStatusCode();

            if (isset($routes[$status])) {
                $func = $routes[$status];
            }
        }

        if ($func === false) {
            $routes = array_filter(self::$httpRoutes);
            $pathinfo = \UtilsPath();
            $httpMethod = $_SERVER['REQUEST_METHOD'];

            $verb = 'ANY ' . $pathinfo;
            $http = $httpMethod . ' ' . $pathinfo;

            if (isset($routes[$verb])) {
                $func = $routes[$verb];
            } elseif (isset($routes[$http])) {
                $func = $routes[$http];
                $verb = $http;
            } elseif (empty($routes) === false) {
                foreach ($routes as $key => $value) {
                    if (strpos($key, ' re:') !== false && self::find($httpMethod, $key, $pathinfo)) {
                        $func = $value;
                        $verb = $key;
                    }
                }
            }
        }

        if ($func === false && self::$noRoutes !== null) {
            $func = self::$noRoutes;
        }

        if ($func !== false) {
            self::$cController = self::$prefixNS . $func;
            self::$cVerb = $verb;
        } else {
            self::$cController = false;
        }

        $routes = self::$statusRoutes = self::$httpRoutes = null;

        return self::$cController;
    }
}

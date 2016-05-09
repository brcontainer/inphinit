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
use Inphinit\Route;

class RouteGroup extends Route
{
    protected static $prefixPath;

    private static function checkDomain($domain)
    {
        $host = $_SERVER['HTTP_HOST'];

        if ($host === $domain) {
            return true;
        }

        return false;
    }

    public static function by(array $opts, \Closure $call)
    {
        if (empty($opts['path']) && empty($opts['domain'])) {
            Exception::raise('path and domain not defined', 2);
            return null;
        }

        if (empty($opts['domain']) === false && self::checkDomain($opts['domain'])) {
            Exception::raise('path and domain not defined', 2);
            return null;
        }

        $path = rtrim($opts['path'], '/') . '/';

        if (strpos(\UtilsPath(), $path) === 0) {
            parent::$prefixPath = rtrim($opts['path'], '/');

            if (empty($opts['namespace']) === false) {
                parent::$prefixNS = $opts['namespace'];
            }

            $call();

            parent::$prefixNS = '';
            parent::$prefixPath = '';
        }
    }
}

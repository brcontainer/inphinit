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

class GroupRoute
{
    private static function checkDomain($domain)
    {
        $host = $_SERVER['HTTP_HOST'];

        if ($host === $domain) {
            return true;
        }

        return false;
    }

    public static function by(array $opts, callable $controllerAction)
    {
        if (empty($opts['path']) && empty($opts['domain'])) {
            Exception::auto('path and domain not defined', 2);
            return null;
        }

        if (empty($opts['domain']) === false && self::checkDomain($opts['domain'])) {
            return null;
        }

        $path = rtrim($opts['path'], '/') . '/';

        if (strpos(\UtilsPath(), $path) === 0) {
            if (empty($opts['namespace']) === false) {
                Route::ns($opts['namespace']);
            }

            $controllerAction();
        }
    }
}

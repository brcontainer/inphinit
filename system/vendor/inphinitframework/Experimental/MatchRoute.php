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

class MatchRoute
{
    public static function set($method, $path, $action)
    {
        $matchVariables = array();

        if (preg_match_all('#\{([a-z]+)\}#', $path, $matchVariables) === 0) {
            return false;
        }

        var_dump($matchVariables[1]);
    }
}

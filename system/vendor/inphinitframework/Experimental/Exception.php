<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

class Exception extends \ErrorException
{
    public static function raise($message, $level = 1, $type = E_USER_ERROR)
    {
        $caller = get_called_class();
        $data   = Debug::caller($level);

        throw new $caller($message, $type, 0, $type, $data['file'], $data['line']);
    }
}

<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

class Exception extends \Exception
{
    public function __construct($message, $code, $file, $line) {
        $this->line = $line;
        $this->file = $file;

        parent::__construct($message, $code);
    }

    public static function put($message, $code, $file, $line)
    {
        throw new Exception($message, $code, $file, $line);
    }

    public static function auto($message, $level = 1, $code = E_USER_ERROR)
    {
        $data = Debug::caller($level);
        throw new Exception($message, $code, $data['file'], $data['line']);
    }
}

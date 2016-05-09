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
    public function setup($file, $line)
    {
        $this->line = $line;
        $this->file = $file;
    }

    public static function raise($message, $level = 1, $type = E_USER_ERROR)
    {
        $data = Debug::caller($level);
        $e = new Exception($message, $type);
        $e->setup($data['file'], $data['line']);

        throw $e;
    }
}

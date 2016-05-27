<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

class String
{
    protected $str = '';
    protected $strLenght = 0;

    public function __construct($str, $encoding = null)
    {
        $this->encoding = empty($encoding) ? mb_internal_encoding() : $encoding;

        $str = '' . $str;

        $this->str = $str;
        $this->updateLength();
    }

    public function __toString()
    {
        return $this->str;
    }

    private function updateLength()
    {
        $this->strLenght = mb_strlen($this->str, $this->encoding);
    }

    public function length()
    {
        return $this->strLenght;
    }

    public function cut($limit)
    {
        return $this;
    }

    public function reverse()
    {
        $this->str = mb_strrev($this->str, $this->encoding);
        return $this;
    }

    public function slug()
    {
        return $this;
    }

    public function camelCase()
    {
        return $this;
    }
}

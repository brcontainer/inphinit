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

    public function __construct($str)
    {
        $str = '' . $str;

        $this->str = $str;
        $this->strLenght = strlen($str);
    }

    public function __toString()
    {
        return $this->str;
    }

    public function __get($name)
    {
        if ($name === 'length') {
            return $this->strLenght;
        }
    }

    public function cut($limit)
    {
        return $this;
    }

    public function invert()
    {
        return $this;
    }

    public function plural()
    {
        return $this;
    }

    public function singular()
    {
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

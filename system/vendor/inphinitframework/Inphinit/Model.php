<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Inphinit;

abstract class Model
{
    protected $keys = array();
    private $content = array();

    public function __construct()
    {
        $keys = array_filter($this->keys);
        $ckey = null;

        foreach ($keys as $key => $value) {
            if (is_numeric($key)) {
                $ckey  = $value;
                $value = null;
            } else {
                $ckey = $key;
            }

            if (preg_match('#[^a-z\d]#i', $ckey) === 0) {
                $this->content[$ckey] = $value;
            }
        }
    }

    public function __set($name, $value) {
        if (array_key_exists($name, $this->content)) {
            $this->content[$name] = $value;
            return null;
        }

        trigger_error('Invalid property ' . __CLASS__ . '->' . $name);
    }

    public function __get($name) {
        if (array_key_exists($name, $this->content)) {
            return $this->content;
        }

        trigger_error('Invalid property ' . __CLASS__ . '->' . $name);
    }

    public function data()
    {
        return $this->content;
    }

    public function __destruct()
    {
        $this->content = null;
        $this->keys = null;
    }
}

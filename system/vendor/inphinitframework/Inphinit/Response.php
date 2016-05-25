<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Inphinit;

class Response
{
    private $output;
    private $fileinf = false;
    private $chunk;
    private $delay;
    private $clean = true;

    private static $httpCode;
    private static $headers = array();
    private static $dispatchedHeaders = false;

    public function __construct()
    {
        App::on('ready', array($this, 'show'));
    }

    public function cleanAfterUse($active = true)
    {
        if ($active === true || $active === false) {
            $this->clean = $active;
        }
    }

    public static function dispatchHeaders()
    {
        $headers = self::$headers;

        if (empty($headers) === false) {
            $lastCode = null;

            self::$dispatchedHeaders = true;

            foreach ($headers as $value) {
                self::putHeader($value[0], $value[1], is_numeric($value[2]) ? $value[2] : null);
            }

            $headers = null;
        }
    }

    public static function getHeaders()
    {
        return self::$headers;
    }

    public static function status($code = null, $preventTrigger = false)
    {
        if (self::$httpCode !== $code && is_int($code) && headers_sent() === false) {
            header('X-PHP-Response-Code: ' . $code, true, $code);
            self::$httpCode = $code;

            if (!$preventTrigger) {
                App::trigger('changestatus', array($code, null));
            }

            return true;
        } elseif (self::$httpCode === null) {
            self::$httpCode = \UtilsStatusCode();
        }

        return self::$httpCode;
    }

    public static function putHeader($header, $replace = true, $code = null)
    {
        if (self::$dispatchedHeaders) {
            if (is_numeric($code)) {
                header($header, $replace, $code);
                self::$httpCode = $code;
                App::on('changestatus', array($code, null));
            } else {
                header($header, $replace);
            }
            return null;
        }

        if (is_string($header) && is_bool($replace) && ($code === null || is_numeric($code))) {
            self::$headers[] = array($header, $replace, $code);
            return count(self::$headers);
        }

        return false;
    }

    public static function removeHeader($index)
    {
        if (self::$dispatchedHeaders && isset(self::$headers[$index])) {
            self::$headers[$index] = null;
            return true;
        }

        return false;
    }

    public static function cache($seconds, $lastModified = null)
    {
        $headers = array();

        if ($seconds === 0) {
            $g = gmdate('D, d M Y H:i:s');
            $headers['Expires: ' . $g . ' GMT'] = true;
            $headers['Last-Modified: ' . $g . ' GMT'] = true;
            $headers['Cache-Control: no-store, no-cache, must-revalidate'] = true;
            $headers['Cache-Control: post-check=0, pre-check=0'] = false;
            $headers['Pragma: no-cache'] = true;
        } elseif ($seconds > 1) {
            $headers['Expires: ' . gmdate('D, d M Y H:i:s', REQUEST_TIME + $seconds) . ' GMT'] = true;
            $headers['Cache-Control: public, max-age=' . $seconds] = true;
            $headers['Pragma: max-age=' . $seconds] = true;

            if (is_int($lastModified)) {
                $headers['Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT'] = true;
            }
        }

        if ($seconds > -1) {
            foreach ($headers as $key => $value) {
                self::putHeader($key, $value);
            }

            $headers = null;
        }
    }

    public static function download($name, $contentLength = false)
    {
        if (is_string($name)) {
            self::putHeader('Content-Transfer-Encoding: Binary');
            self::putHeader('Content-Disposition: attachment; filename="' . strtr($name, '"', '-') . '"');
        }

        if (is_int($contentLength)) {
            self::putHeader('Content-Length: ' . $contentLength);
        }
    }

    public static function type($mime)
    {
        self::putHeader('Content-Type: ' . $mime);
    }

    public function file($path, $chunk = 1024, $delay = 0)
    {
        if (is_file($path)) {
            $this->chunk   = $chunk;
            $this->delay   = $delay;
            $this->fileinf = $path;
            $this->output  = true;

            return true;
        }

        $this->output  = null;
        $this->fileinf = false;
        return false;
    }

    public function data($value = null)
    {
        if ($value === null) {
            return $this->output;
        }

        if ($value === false) {
            $this->output = null;
        } else {
            $this->output = $value;
            $value = null;
        }

        $this->fileinf = false;
    }

    public function json(array $value)
    {
        self::type('application/json');
        $this->data(json_encode($value));
        $value = null;
    }

    public function show()
    {
        if (false === empty($this->output)) {
            $path = $this->fileinf;

            if ($path !== false) {
                File::output($path, $this->chunk, $this->delay);
            } else {
                echo $this->output;
            }
        }

        if ($this->clean) {
            $this->fileinf = false;
            $this->output  = null;

            App::off('ready', array($this, 'show'));
        }
    }
}

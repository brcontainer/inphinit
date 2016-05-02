<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental;

use Inphinit\File;

class Http
{
    private static $transport     = null;
    private static $maxRedirects  = 10;
    private static $timeOut       = 30;

    public function __construct($method, $url, $opts = null)
    {
        //fsockopen
        /*
        $http = array(
            'method' => $method,
            'user_agent' => 'Mozilla/5.0 (Inphinit ' . DIMINISH_VERSION . ')',
            'max_redirects' => 3
        );

        if (is_array($opts)) {
            $http = $http + $opts;
        }

        $context = stream_context_create(array(
            'http' => $http
        ));

        $fp = fopen(URL, 'rb', false, $context);

        // extract the cookies
        $meta    = stream_get_meta_data($fp);
        $headers = $metadata['wrapper_data'];

        fclose($fp);
        */
    }

    public function write($data)
    {
        //fwrite
    }

    public function response()
    {
        //feedback from write
        //fgets
    }

    public function close()
    {
        //fclose
    }

    public function __destruct()
    {
        $this->close();
    }

    public static function setup($timeout = null, $maxRedirects = null)
    {
        if (is_int($timeout)) {
            self::$timeout = $timeout;
        }

        if (is_int($maxRedirects)) {
            self::$maxRedirects = $maxRedirects;
        }
    }

    public static function supportSecurity()
    {
        if (self::$transport !== null) {
            return self::$transport;
        }

        $data = '::' . implode('::', stream_get_transports()) . '::';

        if (stripos($data, '::ssl') !== false) {
            return self::$transport = 'SSL';
        } elseif (stripos($data, '::tsl') !== false) {
            return self::$transport = 'TSL';
        }

        return self::$transport = false;
    }

    public static function download($url, $headers = null, $print = false)
    {
        $uri = parse_url($url);

        if ($uri['scheme'] === 'https' && empty($uri['port'])) {
            $uri['port'] = 443;
        }

        $uri = $uri + array( 'path' => '/', 'port' => 80 );

        $handle = fsockopen($uri['host'], (int) $uri['port'], $errno, $errstr, self::$timeout);

        if (false === $handle) {
            return false;
        }

        $out = 'GET / HTTP/1.0' . EOL;

        $dh = array(
            'Host' => $uri['host'],
            'Connection' => 'close'
        );

        $dh = is_array($headers) ? ($headers + $dh) : $dh;

        foreach ($dh as $key => $value) {
            $out .= $key . ': ' . $value . EOL;
        }

        $headers = $dh = null;

        fwrite($handle, $out . EOL);

        if ($print === true) {
            while (false === feof($handle)) {
                $handle = fgets($handle, 64);
                echo $handle;
                if (strlen($handle) < 64) {
                    echo EOL;
                }
            }

            fclose($handle);

            $handle = null;

            return true;
        }

        $tmpName = File::createTmp();
        $tmp = fopen($tmpName, 'w');

        while (false === feof($handle)) {
            $data = fgets($handle, 64);
            fwrite($tmp, $data);
        }

        fclose($handle);
        fclose($tmp);

        $handle = null;

        return $tmp;
    }
}

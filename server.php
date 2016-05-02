<?php
if (PHP_SAPI !== 'cli-server') {
    header('Content-Type: text/plain', true, 500);
    echo 'server.php is not allowed with "', PHP_SAPI, '", use a command like this:', PHP_EOL,
            'php -S localhost:8000 server.php', PHP_EOL;
    exit;
}

if (defined('INPHINIT_RUNNING_SERVER')) {
    header('Content-Type: text/plain', true, 500);
    echo 'server.php is not allowed', PHP_EOL;
    return false;
}

define('INPHINIT_RUNNING_SERVER', true);

$currentPath = rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/';

$path = urldecode(preg_replace('#\?(.*)$#', '', $_SERVER['REQUEST_URI']));

if ($path !== '/' && file_exists($currentPath . $path)) {
    return false;
}

require_once $currentPath . 'index.php';

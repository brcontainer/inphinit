<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

use Inphinit\App;

define('INIT_APP', microtime(true));
define('ROOT_PATH', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');
define('INPHINIT_PATH', ROOT_PATH . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'boot/autoload.php';

if (App::env('developer') === true) {
    require_once INPHINIT_PATH . 'dev.php';
}

require_once INPHINIT_PATH . 'main.php';

App::exec();

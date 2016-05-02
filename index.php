<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

define('INIT_APP', microtime());
define('ROOT_PATH', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');
define('INPHINIT_PATH', ROOT_PATH . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'boot/autoload.php';

Inphinit\App::prepare();

require_once INPHINIT_PATH . 'main.php';

Inphinit\App::exec();

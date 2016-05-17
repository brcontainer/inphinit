<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

define('REQUEST_TIME', time());
define('EOL', chr(10));

require_once INPHINIT_PATH . 'vendor/inphinitframework/Utils.php';

if (INPHINIT_COMPOSER) {
    require_once INPHINIT_PATH . 'vendor/autoload.php';
} else {
    UtilsAutoload();
}

UtilsConfig();

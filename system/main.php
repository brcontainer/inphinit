<?php
/*
 * Default project
 */

use Inphinit\App;
use Inphinit\Route;
use Experimental\Debug;

if (App::env('developer') === true) {
    Debug::capture('error', 'debug.error');
}

function showMaintenanceMode() {
    $resp = new Inphinit\Response();
    $resp->data('This site is currently down for maintenance and should be back soon!');
}

App::on('maintenance', 'showMaintenanceMode');

Route::status(404, 'HttpErrors:statusError');
Route::status(array(401, 403, 501), 'HttpErrors:statusError');

Route::invalid('HttpErrors:invalidRoute');

Route::set('ANY', '/', 'Home:index');
Route::set('ANY', '/info', 'Examples:info');
Route::set(array('GET', 'POST'), '/foo', 'Examples:blog');
Route::set('GET', 're:#^/foo/(.*)#', 'Home:index');

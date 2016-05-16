<?php
use Inphinit\App;
use Inphinit\View;
use Inphinit\Routing\Route;

use Experimental\Debug;

if (App::env('developer') === true) {
    Debug::capture('error', 'debug.error');
}

App::on('changestatus', function ($status, $msg) {
    if ($status === 503) {
        echo 'This site is currently down for maintenance and should be back soon!';
    } elseif (in_array($status, array(401, 403, 404, 500, 501))) {
        View::forceRender();
        View::render('httpview', array(
            'title'  => $msg ? $msg : 'Página inacessível',
            'method' => $_SERVER['REQUEST_METHOD'],
            'path'   => Inphinit\Request::path(),
            'status' => $status
        ));
        exit;
    }
});

Route::set('ANY', '/', 'Home:index');
Route::set('ANY', 're:#/user/([a-z0-9_]+)$#', 'Users.Profile:view');

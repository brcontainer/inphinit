<?php
namespace Controller;

use Inphinit\View;
use Inphinit\Request;
use Inphinit\Response;

class HttpErrors
{
    public function invalidRoute()
    {
        Response::status(404);//Force 404 in invalid routes

        $data = array(
            'title'       => 'Rota inacessível',
            'method'      => $_SERVER['REQUEST_METHOD'],
            'status'      => Response::status(),
            'querystring' => Request::query(),
            'path'        => $_SERVER['REQUEST_URI'],
            'route'       => \UtilsPath(),
            'isRoute'     => true
        );

        View::render('httpview', $data);
    }

    public function statusError()
    {
        $data = array(
            'title'       => 'Página inacessível',
            'method'      => $_SERVER['REQUEST_METHOD'],
            'status'      => Response::status(),
            'querystring' => Request::query(),
            'path'        => $_SERVER['REQUEST_URI'],
            'isRoute'     => false
        );

        View::render('httpview', $data);
    }
}

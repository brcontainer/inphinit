<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

namespace Experimental\Routing;

class Rest
{
    public function __construct($controller)
    {
        /*
        GET    /photo  index        index
        GET    /photo/create        create
        POST   /photo               store
        GET    /photo/{photo}       show
        GET    /photo/{photo}/edit  edit
        PUT    /photo/{photo}       update
        PATCH  /photo/{photo}       update
        DELETE /photo/{photo}       destroy
        */
    }

    public static function create()
    {
        return new static;
    }

    public function allow(array $methods)
    {
        //
    }

    public function disallow(array $methods)
    {
        //
    }
}

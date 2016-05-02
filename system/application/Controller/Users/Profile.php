<?php
namespace Controller\Users;

use Inphinit\View;

class Profile
{
    public function view()
    {
        $data = array( 'text' => 'test' );
        View::render('home', $data);
    }
}

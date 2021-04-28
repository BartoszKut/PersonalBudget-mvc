<?php

namespace App\Controllers;


abstract class Authenticated extends \Core\Controller
{

    /* Require the user to be logged in before giving access to all methods in this controller */
    protected function before()
    {
        $this -> requireLogin();
    }
    
}
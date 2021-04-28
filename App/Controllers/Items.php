<?php

namespace App\Controllers;

use \Core\View;

class Items extends Authenticated /* Require the user to be logged in before giving access to all methods in this controller */
{

    /* Items index */
    public function indexAction()
    {       
        View::renderTemplate('LoggedIn/mainMenu.html');               
    }



    public function newAction()
    {
        echo "new action";
    }

    
}
<?php

namespace App\Controllers;

use \Core\View;

class Items extends \Core\Controller
{

    /* Items index */
    public function indexAction()
    {       
        if($this->requireLogin()) {
           View::renderTemplate('LoggedIn/mainMenu.html');               
        }
    }
}
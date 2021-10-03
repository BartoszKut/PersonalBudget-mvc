<?php

namespace App\Controllers;

use \Core\View;
use App\Auth;

class AppSettings extends \Core\Controller
{

    /* Settings index */
    public function indexAction()
    {       
        if($this->requireLogin()) {
            View::renderTemplate('AppSetting/settings.html'); 
        }
    }

}

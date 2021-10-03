<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\UserSettings;

class UserSetting extends \Core\Controller
{
    public function newAction()
    {
        if($this->requireLogin()) {
            View::renderTemplate('AppSetting/userSettings.html');
        };
    }


    
    public function updateUserAction()
    {   
        if($this->requireLogin()) {        
            $userSettings = new UserSettings($_POST);

            if($userSettings -> updateUserData()) {
                View::renderTemplate('LoggedIn/mainMenu.html');
            }            
        }
    }
}
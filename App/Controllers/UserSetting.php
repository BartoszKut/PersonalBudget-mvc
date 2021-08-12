<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\UserSettings;

class UserSetting extends Authenticated 
{
    public function newAction()
    {
        View::renderTemplate('AppSetting/userSettings.html');
    }


    
    public function updateUserAction()
    {   
        $userSettings = new UserSettings($_POST);

        if($userSettings -> updateUserData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }
}
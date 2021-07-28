<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomesSettings;

class IncomeSettings extends Authenticated 
{
    public function newAction()
    {       
        $arguments['incomesCategories'] = IncomesSettings::getIncomesCategories();

        View::renderTemplate('AppSetting/incomesSettings.html');               
    }



    public function updateIncomes()
    {   
        $incomesSettings = new IncomesSettings($_POST);

        if($incomesSettings -> updateIncomesData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }

}
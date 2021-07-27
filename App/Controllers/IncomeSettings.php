<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomesSettings;

class IncomeSettings extends Authenticated 
{
    public function newAction()
    {       
        View::renderTemplate('AppSetting/incomesSettings.html');               
    }



    public function updateIncomes()
    {   
        $incomeSettings = new IncomeSettings($_POST);

        if($incomeSettings -> updateIncomesData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }

}
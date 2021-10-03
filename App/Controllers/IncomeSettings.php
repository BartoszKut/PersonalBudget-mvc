<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomesSettings;
use \App\Models\Income;

class IncomeSettings extends \Core\Controller
{
    public function newAction()
    {     
        if($this->requireLogin()) {
            $arguments['incomesCategories'] = Income::getIncomesCategories();

            View::renderTemplate('AppSetting/incomesSettings.html', $arguments);       
        }   
    }


    public function updateIncomesAction()
    {   
        $incomesSettings = new IncomesSettings($_POST);

        if($incomesSettings -> updateIncomesData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }
    
}
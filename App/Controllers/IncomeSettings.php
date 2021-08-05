<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomesSettings;
use \App\Models\Income;

class IncomeSettings extends Authenticated 
{
    public function newAction()
    {     
        $arguments['incomesCategories'] = Income::getIncomesCategories();

        View::renderTemplate('AppSetting/incomesSettings.html', $arguments);          
    }



    public function updateIncomes()
    {   
        $incomesSettings = new IncomesSettings($_POST);

        if($incomesSettings -> updateIncomesData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }



    public function modalUpdateIncomes()
    {   
        $incomesSettings = new IncomesSettings($_POST);

        var_dump($incomesSettings);

        echo 'działa';

        if($incomesSettings -> updateModalIncomesData()) {
            //View::renderTemplate('LoggedIn/mainMenu.html');
            echo 'działa';
        }            
    }


}
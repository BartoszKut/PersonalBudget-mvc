<?php

namespace App\Controllers;

use \Core\View;

class AppSettings extends Authenticated /* Require the user to be logged in before giving access to all methods in this controller */
{

    /* Settings index */
    public function indexAction()
    {       
        View::renderTemplate('AppSetting/settings.html');               
    }



    public function editIncomes()
    {       
        View::renderTemplate('AppSetting/incomesSettings.html');               
    }


    public function editExpenses()
    {       
        View::renderTemplate('AppSetting/expensesSettings.html');               
    }
    
}
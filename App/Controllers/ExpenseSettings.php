<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\ExpensesSettings;
use \App\Models\Expense;

class ExpenseSettings extends Authenticated 
{
    public function newAction()
    {     
        $arguments['expensesCategories'] = Expense::getExpensesCategories();
        $arguments['paymentMethods'] = Expense::getPaymentMethods();

        View::renderTemplate('AppSetting/expensesSettings.html', $arguments);          
    }



    public function updateExpenses()
    {   
        $expensesSettings = new ExpensesSettings($_POST);

        if($expensesSettings -> updateExpensesData()) {
            View::renderTemplate('LoggedIn/mainMenu.html');
        }            
    }

}
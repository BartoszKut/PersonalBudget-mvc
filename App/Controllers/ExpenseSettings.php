<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\ExpensesSettings;
use \App\Models\Expense;

class ExpenseSettings extends \Core\Controller
{
    public function newAction()
    {     
        if($this->requireLogin()) {
            $arguments['expensesCategories'] = Expense::getExpensesCategories();
            $arguments['paymentMethods'] = Expense::getPaymentMethods();

            View::renderTemplate('AppSetting/expensesSettings.html', $arguments);     
        }     
    }



    public function updateExpenses()
    {   
        if($this->requireLogin()) {
            $expensesSettings = new ExpensesSettings($_POST);

            if($expensesSettings -> updateExpensesData()) {
                View::renderTemplate('LoggedIn/mainMenu.html');
            }           
        } 
    }

}
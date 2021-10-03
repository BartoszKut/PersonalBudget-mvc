<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Controllers\Items;

/* Add expense controller */
class Expenses extends \Core\Controller
{

    /* Show the add expense page */
    public function newAction()
    {
        if($this->requireLogin()) {        
            $arguments = [];
            $arguments['expensesCategories'] = Expense::getExpensesCategories();
            $arguments['paymentMethods'] = Expense::getPaymentMethods();

            View::renderTemplate('Expense/new.html', $arguments);
        }
    }


    /* Add new expense */
    public function createAction()
    {
        $expense = new Expense($_POST);
 
        if ($expense -> save()){
            $this -> redirect('/items/index');
        } else {
            View::renderTemplate('Expense/new.html', [
                'expense' => $expense
            ]);
        }
    }


    public function getCategoryLimitAction() {
        
        $post = file_get_contents('php://input');
        $data = json_decode($post);
        $category = $data -> category;
  
        $categoryLimit = Expense::getExpenseLimit($category);

        if($categoryLimit == NULL) {
            echo "-";
        } else {
            echo $categoryLimit;
        }

        
    }


    public function getSummaryExpenseAction() {

        $post = file_get_contents('php://input');
        $data = json_decode($post);
        $category = $data -> category;
        $date = $data -> date;

        $firstDayOfMonth = Expense::getFirstDayOfMonth($date);
        $lastDayOfMontyh = Expense::getLastDayOfMonth($date);

        $summaryExpenses = Expense::getSumOfExpensesOfChosenCategory($firstDayOfMonth, $lastDayOfMontyh, $category);

        if($summaryExpenses == NULL) {
            echo "-";
        } else {
            echo $summaryExpenses;
        }
    }
    
}

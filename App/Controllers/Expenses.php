<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Controllers\Items;

/* Add expense controller */
class Expenses extends Authenticated
{

    /* Show the add expense page */
    public function newAction()
    {
        $arguments = [];
        $arguments['expensesCategories'] = Expense::getExpensesCategories();
        $arguments['paymentMethods'] = Expense::getPaymentMethods();

        View::renderTemplate('Expense/new.html', $arguments);
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



    public function getExpenseSummaryAction() {
        $date = $this -> route_params['date'];
        $expenseSummary = Expense::getExpenseSummary($date);

        header('Content-Type: application/json');
        echo json_encode($expenseSummary);
    }

}

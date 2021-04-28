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
        View::renderTemplate('Expense/new.html');
    }


    /* Add new expense */
    public function createAction()
    {
        $expense = new Expense($_POST);
 
        if ($expense -> save()){
            $this -> redirect('/PersonalBudget-mvc/public/items/index');
        } else {
            View::renderTemplate('Expense/new.html', [
                'expense' => $expense
            ]);
        }
    }

}

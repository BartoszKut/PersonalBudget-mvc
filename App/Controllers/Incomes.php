<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Controllers\Items;
use \App\Auth;

/* Add incomes controller */
class Incomes extends \Core\Controller
{
    /* Show the add income page */
    public function newAction()
    {
        if($this->requireLogin()) {
            $arguments['incomesCategories'] = Income::getIncomesCategories();

            View::renderTemplate('Income/new.html', $arguments);
        }
    }


    /* Add new income */
    public function createAction()
    {
        if($this->requireLogin()) {
            $income = new Income($_POST);
    
            if ($income -> save()){
                $this -> redirect('/items/index');
            } else {
                View::renderTemplate('Income/new.html', [
                    'income' => $income
                ]);
            }
        }
    }

}

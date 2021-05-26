<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Controllers\Items;

/* Add incomes controller */
class Incomes extends Authenticated
{

    /* Show the add income page */
    public function newAction()
    {
        View::renderTemplate('Income/new.html');
    }


    /* Add new income */
    public function createAction()
    {
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

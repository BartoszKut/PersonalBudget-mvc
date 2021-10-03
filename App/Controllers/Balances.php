<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Controllers\Items;
use \App\Flash;

use \Datetime;


class Balances extends \Core\Controller
{
    public function dataToRender($firstDay, $lastDay)
    {
        $args = [];

        $args['incomes_sum'] = Balance::getSumOfIncomes($firstDay, $lastDay);
        $args['expenses_sum'] = Balance::getSumOfExpenses($firstDay, $lastDay);
        $args['incomes'] = Balance::getIncomes($firstDay, $lastDay);
        $args['incomes_details'] = Balance::getDetailsOfIncomes($firstDay, $lastDay);
        $args['expenses'] = Balance::getExpenses($firstDay, $lastDay);
        $args['expenses_details'] = Balance::getDetailsOfExpenses($firstDay, $lastDay);

        return $args;
    }


    public function currentAction()
    {
        if($this->requireLogin()) {
            $firstDay = new DateTime('first day of this month');  
            $firstDay = $firstDay -> format('Y-m-d');

            $lastDay = new DateTime('last day of this month');        
            $lastDay = $lastDay -> format('Y-m-d');

            $arguments = [];
            $arguments['currentMonth'] = Balance::getPolishNameOfCurrentMonth();
            $arguments['information'] = Balance::getBalanceFromCurrentMonth();  

            $args = $this->dataToRender($firstDay, $lastDay);

            $allArguments = array_merge($arguments, $args);
            
            View::renderTemplate('Balance/CurrentMonth.html', $allArguments);
        }
    }


    public function previousAction()
    {
        if($this->requireLogin()) {
            $firstDay = new DateTime('first day of previous month');  
            $firstDay = $firstDay -> format('Y-m-d');

            $lastDay = new DateTime('last day of previous month');
            $lastDay = $lastDay -> format('Y-m-d');

            $arguments = [];
            $arguments['previousMonth'] = Balance::getPolishNameOfPreviousMonth();
            $arguments['information'] = Balance::getBalanceFromPreviousMonth();   

            $args = $this->dataToRender($firstDay, $lastDay);

            $allArguments = array_merge($arguments, $args);
            
            View::renderTemplate('Balance/PreviousMonth.html', $allArguments);
        }
    }


    public function selectedDatesAction()
    {
        if($this->requireLogin()) {
            $firstDay = $_POST['firstDate'];
            $lastDay = $_POST['secondDate'];

            if($firstDay == "" || $lastDay == "") {
                Flash::addMessage("Należy wybrać obie daty!");
                $this -> selectDatesAction();
            }
            else if($firstDay > $lastDay) {
                Flash::addMessage("Pierwsza data nie może być późniejsza niż druga!");
                $this -> selectDatesAction();
            }
            else {
                $arguments = [];
                $arguments['firstDate'] = $firstDay;
                $arguments['secondDate'] = $lastDay;
                $arguments['information'] = Balance::getBalanceFromSelectedDates($firstDay, $lastDay);

                $args = $this->dataToRender($firstDay, $lastDay);

                $allArguments = array_merge($arguments, $args);
                
                View::renderTemplate('Balance/SelectedDates.html', $allArguments);
            }
        }
    }


    public function selectDatesAction()
    {
        if($this->requireLogin()) {
            View::renderTemplate('Balance/SelectDates.html'); 
        }
    }

}

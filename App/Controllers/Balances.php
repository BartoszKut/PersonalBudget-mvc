<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Controllers\Items;
use \App\Flash;

use \Datetime;


class Balances extends Authenticated
{
    public function selectedDatesBalanceTemplate($firstDate, $secondDate)
    {
        $arguments = [];
        $arguments['firstDate'] = $firstDate;
        $arguments['secondDate'] = $secondDate;
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDate, $secondDate);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDate, $secondDate);
        $arguments['incomes'] = Balance::getIncomes($firstDate, $secondDate);
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        $arguments['information'] = ($amount);     
        
        View::renderTemplate('Balance/SelectedDates.html', $arguments);
    }



    public function currentAction()
    {
        $firstDay = new DateTime('first day of this month');  
        $firstDay = $firstDay -> format('Y-m-d');

        $lastDay = new DateTime('last day of this month');        
        $lastDay = $lastDay -> format('Y-m-d');

        $arguments = [];
        $arguments['currentMonth'] = Balance::getPolishNameOfCurrentMonth();
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDay, $lastDay);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDay, $lastDay);
        $arguments['incomes'] = Balance::getIncomes($firstDay, $lastDay);
        $arguments['incomes_details'] = Balance::getDetailsOfIncomes($firstDay, $lastDay);
        $arguments['expenses'] = Balance::getExpenses($firstDay, $lastDay);
        $arguments['expenses_details'] = Balance::getDetailsOfExpenses($firstDay, $lastDay);
        $arguments['information'] = Balance::getBalanceFromCurrentMonth();  
        
        View::renderTemplate('Balance/CurrentMonth.html', $arguments);
    }



    public function previousAction()
    {
        $firstDay = new DateTime('first day of previous month');  
        $firstDay = $firstDay -> format('Y-m-d');

        $lastDay = new DateTime('last day of previous month');
        $lastDay = $lastDay -> format('Y-m-d');

        $arguments = [];
        $arguments['previousMonth'] = Balance::getPolishNameOfPreviousMonth();
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDay, $lastDay);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDay, $lastDay);
        $arguments['incomes'] = Balance::getIncomes($firstDay, $lastDay);
        $arguments['expenses'] = Balance::getExpenses($firstDay, $lastDay);
        //$arguments['information'] = Balance::saveOrNo($amount);       
        
        View::renderTemplate('Balance/PreviousMonth.html', $arguments);
    }



    public function selectedDatesAction()
    {
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
            $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDay, $lastDay);
            $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDay, $lastDay);
            $arguments['incomes'] = Balance::getIncomes($firstDay, $lastDay);
            $arguments['expenses'] = Balance::getExpenses($firstDay, $lastDay);
            //$arguments['information'] = saveOrNo($amount);     
            
            View::renderTemplate('Balance/SelectedDates.html', $arguments);
        }
    }



    public function selectDatesAction()
    {
       View::renderTemplate('Balance/SelectDates.html'); 
    }
}

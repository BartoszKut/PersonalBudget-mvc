<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Controllers\Items;
use \App\Flash;

use \Datetime;


class Balances extends Authenticated
{

    public function currentMonthBalanceTemplate($firstDate, $secondDate)
    {
        $arguments = [];
        $arguments['currentMonth'] = Balance::getPolishNameOfCurrentMonth();
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDate, $secondDate);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDate, $secondDate);
        $arguments['incomes'] = Balance::getIncomes($firstDate, $secondDate);
        $arguments['incomes_details'] = Balance::getDetailsOfIncomes($firstDate, $secondDate);
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        $arguments['expenses_details'] = Balance::getDetailsOfExpenses($firstDate, $secondDate);
        $arguments['information'] = Balance::getBalanceFromCurrentMonth();  
        
        View::renderTemplate('Balance/CurrentMonth.html', $arguments);
    }



    public function previousMonthBalanceTemplate($firstDate, $secondDate)
    {
        $arguments = [];
        $arguments['previousMonth'] = Balance::getPolishNameOfPreviousMonth();
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDate, $secondDate);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDate, $secondDate);
        $arguments['incomes'] = Balance::getIncomes($firstDate, $secondDate);
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        //$arguments['information'] = Balance::saveOrNo($amount);       
        
        View::renderTemplate('Balance/PreviousMonth.html', $arguments);
    }



    public function selectedDatesBalanceTemplate($firstDate, $secondDate)
    {
        $arguments = [];
        $arguments['firstDate'] = $firstDate;
        $arguments['secondDate'] = $secondDate;
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDate, $secondDate);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDate, $secondDate);
        $arguments['incomes'] = Balance::getIncomes($firstDate, $secondDate);
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        //$arguments['information'] = saveOrNo($amount);     
        
        View::renderTemplate('Balance/SelectedDates.html', $arguments);
    }



    public function currentAction()
    {
        $firstDay = new DateTime('first day of this month');  
        $lastDay = new DateTime('last day of this month');

        $this -> currentMonthBalanceTemplate($firstDay, $lastDay);
    }



    public function previousAction()
    {
        $firstDay = new DateTime('first day of previous month');  
        $lastDay = new DateTime('last day of previous month');

        $this -> previousMonthBalanceTemplate($firstDay, $lastDay);
    }



    public function selectedDatesAction()
    {
        $firstDate = strtotime($_POST['firstDate']);
        $firstdate = date("Y-m-d", $firstDate);
        $firstDay = new DateTime($firstdate);

        $lastDate = strtotime($_POST['secondDate']);
        $lastdate = date("Y-m-d", $lastDate);
        $lastDay = new DateTime($lastdate);

        if($firstDay == "" || $lastDay == "") {
            Flash::addMessage("Należy wybrać obie daty!");
            $this -> selectDatesAction();
        }
        else if($firstDay > $lastDay) {
            Flash::addMessage("Pierwsza data nie może być późniejsza niż druga!");
            $this -> selectDatesAction();
        }

        $this -> selectedDatesBalanceTemplate($firstDay, $lastDay);
    }



    public function selectDatesAction()
    {
       View::renderTemplate('Balance/SelectDates.html'); 
    }
}

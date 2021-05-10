<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Controllers\Items;

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
        $$arguments['previousMonth'] = Balance::getPolishNameOfPreviousMonth();
        $arguments['incomes_sum'] = Balance::getSumOfIncomes($firstDate, $secondDate);
        $arguments['expenses_sum'] = Balance::getSumOfExpenses($firstDate, $secondDate);
        $arguments['incomes'] = Balance::getIncomes($firstDate, $secondDate);
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        $arguments['information'] = saveOrNo($amount);       
        
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
        $arguments['incomes_details'] = Balance::getDetailsOfIncome();
        $arguments['expenses'] = Balance::getExpenses($firstDate, $secondDate);
        $arguments['information'] = saveOrNo($amount);     
        
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
        if($_POST['firstDate'] == '' || $_POST['secondDate'] == '') {
            Flash::addMessage("Należy wybrać obie daty!");
            $this -> currentAction();
        }
        else if($_POST['firstDate'] > $_POST['secondDate'] == '') {
            Flash::addMessage("Pierwsza data nie może być późniejsza niż druga!");
            $this -> currentAction();
        }

        $firstDay = $_POST['firstDate'];
        $lastDay = $_POST['secondDate'];

        $this -> previousMonthBalanceTemplate($firstDay, $lastDay);
    }


}

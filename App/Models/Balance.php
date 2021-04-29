<?php

namespace App\Models;

use PDO;

/* Example expense model */
class Expense extends \Core\Model
{
    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }



    /* get month in integer from date */
    protected function getMonthInInt($date)
    {      
        $whichMonth = strtotime($date);
        $month_str = date("m",$whichMonth);
        $month = intval($month_str);

        return $month;
    }



    /* get year in integer from date */
    protected function getYearInInt($date)
    {
        // I used 'd', because in Pol we use format of date: dd-mm-yy, but default value of format in date functionm is year at the begining
        $year = date('d', strtotime($date));

        return $year;
    }



    /* get name of month in polish */
    protected function getPolishNameOfMonth($date)
    {
        $month = getMonthInInt($date);

        if($month == 1) $month_in_string_pl = "styczeń";
        else if($month == 2) $month_in_string_pl = "luty";
        else if($month == 3) $month_in_string_pl = "marzec";
        else if($month == 4) $month_in_string_pl = "kwiecień";
        else if($month == 5) $month_in_string_pl = "maj";
        else if($month == 6) $month_in_string_pl = "czerwiec";
        else if($month == 7) $month_in_string_pl = "lipiec";
        else if($month == 8) $month_in_string_pl = "sierpień";
        else if($month == 9) $month_in_string_pl = "wrzesień";
        else if($month == 10) $month_in_string_pl = "październik";
        else if($month == 11) $month_in_string_pl = "listopad";
        else if($month == 12) $month_in_string_pl = "grudzień";

        return $month_in_string_pl;
    }



    /* get quantity of days in mont */
    protected function howManyDays($date)
    {
        $monthInInt = getMonthInInt($date);
        $yearInInt = getYearInInt($date);

        if($montInInt == (1 || 3 || 5 || 7 || 8 || 10 || 12))$quantityOfDayes = 31;
        else if ($monthInInt == (4 || 6 || 9 || 11)) $quantityOfDayes = 30;
        else if ($monthInInt == 2 && ($yearInInt % 4 == 0)) $quantityOfDayes = 29;
        else if ($monthInInt == 2 && ($yearInInt % 4 != 0)) $quantityOfDayes = 28;

        return $quantityOfDayes;
    }



    /* get incomes from database */
    public function getIncomes($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_incomes = 'SELECT category_incomes.name as Category, SUM(incomes.amount) as Amount FROM incomes INNER JOIN incomes_category_assigned_to_users as category_incomes WHERE incomes.income_category_assigned_to_user_id = category_incomes.id AND incomes.user_id = :id_user AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date GROUP BY Category ORDER BY Amount DESC';

        $db_get_incomes = static::getDB();
        $stmt_incomes = $db_get_incomes -> prepare($sql_get_incomes);

        $stmt_incomes->bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_incomes->bindValue(':first_date', $first_date, PDO::PARAM_STR);
        $stmt_incomes->bindValue(':second_date', $second_date, PDO::PARAM_STR);
        $stmt_incomes->execute();

        $result_sum_of_incomes = $stmt_incomes -> fetchAll();

            foreach($result_sum_of_incomes as $month_incomes) {
                $sql_incomes_details = 'SELECT incomes.date_of_income as Date, incomes.income_comment as Comment, incomes.amount as Amount FROM incomes INNER JOIN incomes_category_assigned_to_users as category_incomes WHERE incomes.income_category_assigned_to_user_id = category_incomes.id AND incomes.user_id= :id_user AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date AND category_incomes.name = :category_name ORDER BY Date';

                $db_incomes_details = static::getDB();
                $stmt_incomes_details = $db_incomes_details -> prepare($sql_incomes_details);

                $stmt_incomes_details -> bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
                $stmt_incomes_details -> bindValue(':first_date', $first_date, PDO::PARAM_STR);
                $stmt_incomes_details -> bindValue(':second_date', $second_date, PDO::PARAM_STR);  
                $stmt_incomes_details -> bindValue(':category_name', $month_incomes[0], PDO::PARAM_INT);   
                $stmt_incomes_details -> execute();

                $result_incomes_details = $stmt_incomes_details -> fetchAll();
            }

        $sql_incomes_sum = 'SELECT SUM(amount) FROM incomes WHERE user_id = :logged_user_id AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date';

        $db_incomes_sum = static::getDB();
        $stmt_incomes_sum = $db_incomes_sum -> prepare($sql_incomes_sum);

        $stmt_incomes_sum -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);
        $stmt_incomes_sum -> bindValue(':first_date', $first_date, PDO::PARAM_STR);
        $stmt_incomes_sum -> bindValue(':second_date', $second_date, PDO::PARAM_STR);  
        $stmt_incomes_sum -> execute();
        $incomes_sum = $stmt_incomes_sum -> fetchColumn();

        return $incomes_sum;
    }



    public function getExpenses($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_expenses = 'SELECT category_expenses.name as category, SUM(expenses.amount) as amount FROM expenses INNER JOIN expenses_category_assigned_to_users as category_expenses WHERE expenses.expense_category_assigned_to_user_id = category_expenses.id AND expenses.user_id = :id_user AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date GROUP BY category ORDER BY amount DESC';

        $db_get_expenses = static::getDB();
        $stmt_expenses = $db_get_expenses -> prepare($sql_get_expenses);

        $stmt_expenses->bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_expenses->bindValue(':first_date', $first_date, PDO::PARAM_STR);
        $stmt_expenses->bindValue(':second_date', $second_date, PDO::PARAM_STR);
        $stmt_expenses->execute();

        $result_sum_of_expenses = $stmt_expenses -> fetchAll();

            foreach($result_sum_of_expenses as $month_expenses) {
                $sql_expenses_details = 'SELECT expenses.date_of_expense as date, expenses.expense_comment as comment, expenses.amount as amount FROM expenses INNER JOIN expenses_category_assigned_to_users as category_expenses WHERE expenses.expense_category_assigned_to_user_id = category_expenses.id AND expenses.user_id= :id_user AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date AND category_expenses.name = :category_name ORDER BY date';

                $db_expenses_details = static::getDB();
                $stmt_expenses_details = $db_expenses_details -> prepare($sql_expenses_details);

                $stmt_expenses_details -> bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
                $stmt_expenses_details -> bindValue(':first_date', $first_date, PDO::PARAM_STR);
                $stmt_expenses_details -> bindValue(':second_date', $second_date, PDO::PARAM_STR);  
                $stmt_expenses_details -> bindValue(':category_name', $month_incomes[0], PDO::PARAM_INT);   
                $stmt_expenses_details -> execute();

                $result_expenses_details = $stmt_expenses_details -> fetchAll();
            }

        $sql_expenses_sum = 'SELECT SUM(amount) FROM expenses WHERE user_id = :logged_user_id AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date';

        $db_expenses_sum = static::getDB();
        $stmt_expenses_sum = $db_expenses_sum -> prepare($sql_expenses_sum);

        $stmt_expenses_sum -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);
        $stmt_expenses_sum -> bindValue(':first_date', $first_date, PDO::PARAM_STR);
        $stmt_expenses_sum -> bindValue(':second_date', $second_date, PDO::PARAM_STR);  
        $stmt_expenses_sum -> execute();
        $expenses_sum = $stmt_expenses_sum -> fetchColumn();

        return $expenses_sum;
    }
    



    /* get balance from database */
    public function getBalanceFromCurrentMonth()
    {
        $month_in_polish = getPolishNameOfMonth($date);

        // first day of current month 
        $firstDate = new DateTime('first day of this month');
        $firstDate -> format('Y-m-d');

        // last day of current month 
        $secondDate = new DateTime('last day of this month');
        $secondDate -> format('Y-m-d');

        //save or no
        $incomes_sum = getIncomes($firstDate, $second_date);
        $expenses_sum = getExpenses($firstDate, $second_date);

        $balance = $incomes_sum - $expenses_sum;
        if($balance > 0) $_SESSION['information'] = "Zaoszczędziłeś ".$balance." zł";
        else if($balance < 0){
        $balance = abs($incomes_sum - $expenses_sum);
        $_SESSION['information'] = "Twoje wydatki były o ".$balance." zł większe niż przychody";
        } 
        else if($balance == 0)$_SESSION['information'] = "Twoje wydatki były równe Twoim przychodom";
    }










}
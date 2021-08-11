<?php

namespace App\Models;

use Core\View;
use PDO;
use Datetime;

/* Example expense model */
class Balance extends \Core\Model
{
    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }



    /* get month in integer from date */
    public static function getMonthInInt($date)
    {      
        $whichMonth = strtotime($date);
        $month_str = date("m",$whichMonth);
        $month = intval($month_str);

        return $month;
    }



    /* get year in integer from date */
    public function getYearInInt($date)
    {
        // I used 'd', because in Pol we use format of date: dd-mm-yy, but default value of format in date functionm is year at the begining
        $year = date('d', strtotime($date));

        return $year;
    }



    public static function getPolishNamesOfMonths($month) {
        switch($month) {
            case 1:
                $month_in_string_pl = "styczeń";
                break;
            case 2:
                $month_in_string_pl = "luty";
                break;
            case 3:
                $month_in_string_pl = "marzec";
                break;
            case 4:
                $month_in_string_pl = "kwiecień";
                break;
            case 5:
                $month_in_string_pl = "maj";
                break;
            case 6:
                $month_in_string_pl = "czerwiec";
                break;
            case 7:
                $month_in_string_pl = "lipiec";
                break;
            case 8:
                $month_in_string_pl = "sierpień";
                break;
            case 9:
                $month_in_string_pl = "wrzesień";
                break;
            case 10:
                $month_in_string_pl = "październik";
                break;
            case 11:
                $month_in_string_pl = "listopad";
                break;
            case 12:
                $month_in_string_pl = "grudzień";
                break;         
            }
        return $month_in_string_pl;
    }



    /* get name of current month in polish */
    public static function getPolishNameOfCurrentMonth()
    {   
        $date = new DateTime();       

        $month = static::getMonthInInt($date -> format('Y-m-d'));

        $nameOfCurrentMonth = static::getPolishNamesOfMonths($month);
            
        return $nameOfCurrentMonth;
    }


    /* get name of previous month in polish */
    public static function getPolishNameOfPreviousMonth()
    {
        $date = new DateTime();       

        $month = static::getMonthInInt($date -> format('Y-m-d'));
        
        $month -= 1;

        $nameOfPreviousMonth = static::getPolishNamesOfMonths($month);

        return $nameOfPreviousMonth;        
    }



    /* get quantity of days in month */
    public function howManyDays($date)
    {
        $monthInInt = getMonthInInt($date);
        $yearInInt = getMonthInInt($date);

        if($montInInt == (1 || 3 || 5 || 7 || 8 || 10 || 12))$quantityOfDayes = 31;
        else if ($monthInInt == (4 || 6 || 9 || 11)) $quantityOfDayes = 30;
        else if ($monthInInt == 2 && ($yearInInt % 4 == 0)) $quantityOfDayes = 29;
        else if ($monthInInt == 2 && ($yearInInt % 4 != 0)) $quantityOfDayes = 28;

        return $quantityOfDayes;
    }



    /* get incomes from database */
    public static function getIncomes($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_incomes = 'SELECT category_incomes.name as Category, SUM(incomes.amount) as Amount FROM incomes INNER JOIN incomes_category_assigned_to_users as category_incomes WHERE incomes.income_category_assigned_to_user_id = category_incomes.id AND incomes.user_id = :id_user AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date GROUP BY Category ORDER BY Amount DESC';

        $db_get_incomes = static::getDB();
        $stmt_incomes = $db_get_incomes -> prepare($sql_get_incomes);

        $stmt_incomes -> bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_incomes->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_incomes -> bindValue(':second_date', $secondDate, PDO::PARAM_STR);
        $stmt_incomes -> execute();

        $result_sum_of_incomes = $stmt_incomes -> fetchAll();

        return $result_sum_of_incomes;
    }



    public static function getDetailsOfIncomes($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_incomes = 'SELECT category_incomes.name as Category, SUM(incomes.amount) as Amount FROM incomes INNER JOIN incomes_category_assigned_to_users as category_incomes WHERE incomes.income_category_assigned_to_user_id = category_incomes.id AND incomes.user_id = :id_user AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date GROUP BY Category ORDER BY Amount DESC';

        $db_get_incomes = static::getDB();
        $stmt_incomes = $db_get_incomes -> prepare($sql_get_incomes);

        $stmt_incomes->bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_incomes->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_incomes->bindValue(':second_date', $secondDate, PDO::PARAM_STR);
        $stmt_incomes->execute();

        $result_sum_of_incomes = $stmt_incomes -> fetchAll();

        foreach($result_sum_of_incomes as $month_incomes) {
            $sql_incomes_details = 'SELECT category_incomes.name as Category, incomes.date_of_income as Date, incomes.income_comment as Comment, incomes.amount as Amount FROM incomes INNER JOIN incomes_category_assigned_to_users as category_incomes WHERE incomes.income_category_assigned_to_user_id = category_incomes.id AND incomes.user_id
            = :id_user AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date AND category_incomes.name = :category_name ORDER BY Date';

            $db_incomes_details = static::getDB();
            $stmt_incomes_details = $db_incomes_details -> prepare($sql_incomes_details);

            $stmt_incomes_details -> bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
            $stmt_incomes_details -> bindValue(':first_date', $firstDate, PDO::PARAM_STR);
            $stmt_incomes_details -> bindValue(':second_date', $secondDate, PDO::PARAM_STR);  
            $stmt_incomes_details -> bindValue(':category_name', $month_incomes[0], PDO::PARAM_INT);   
            $stmt_incomes_details -> execute();

            $result_incomes_details = $stmt_incomes_details -> fetchAll();

            return $result_incomes_details;
        }        
    }

    

    /* get sum of incomes from database */
    public static function getSumOfIncomes($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_incomes_sum = 'SELECT SUM(amount) FROM incomes WHERE user_id = :logged_user_id AND incomes.date_of_income >= :first_date AND incomes.date_of_income <= :second_date';

        $db_incomes_sum = static::getDB();
        $stmt_incomes_sum = $db_incomes_sum -> prepare($sql_incomes_sum);

        $stmt_incomes_sum -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);
        $stmt_incomes_sum -> bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_incomes_sum -> bindValue(':second_date', $secondDate, PDO::PARAM_STR);  
        $stmt_incomes_sum -> execute();
        $incomes_sum = $stmt_incomes_sum -> fetchColumn();

        return $incomes_sum;
    }



    /* get expenses from database */
    public static function getExpenses($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_expenses = 'SELECT category_expenses.name as category, SUM(expenses.amount) as amount FROM expenses INNER JOIN expenses_category_assigned_to_users as category_expenses WHERE expenses.expense_category_assigned_to_user_id = category_expenses.id AND expenses.user_id = :id_user AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date GROUP BY category ORDER BY amount DESC';

        $db_get_expenses = static::getDB();
        $stmt_expenses = $db_get_expenses -> prepare($sql_get_expenses);

        $stmt_expenses->bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_expenses->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_expenses->bindValue(':second_date', $secondDate, PDO::PARAM_STR);
        $stmt_expenses->execute();

        $result_sum_of_expenses = $stmt_expenses -> fetchAll();

        return $result_sum_of_expenses;
    }



    public static function getDetailsOfExpenses($firstDate, $secondDate)
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_get_expenses = 'SELECT category_expenses.name as category, SUM(expenses.amount) as amount FROM expenses INNER JOIN expenses_category_assigned_to_users as category_expenses WHERE expenses.expense_category_assigned_to_user_id = category_expenses.id AND expenses.user_id = :id_user AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date GROUP BY category ORDER BY amount DESC';

        $db_get_expenses = static::getDB();
        $stmt_expenses = $db_get_expenses -> prepare($sql_get_expenses);

        $stmt_expenses->bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
        $stmt_expenses->bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_expenses->bindValue(':second_date', $secondDate, PDO::PARAM_STR);
        $stmt_expenses->execute();

        $result_sum_of_expenses = $stmt_expenses -> fetchAll(PDO::FETCH_COLUMN, 0);
        //$result_sum_of_expenses = $stmt_expenses -> fetchAll();

        //var_dump($result_sum_of_expenses);
        
        echo 'PHP version: ' . phpversion();

            foreach($result_sum_of_expenses as $month_expenses) {
                $sql_expenses_details = 'SELECT category_expenses.name as category, expenses.date_of_expense as date, expenses.expense_comment as comment, expenses.amount as amount FROM expenses INNER JOIN expenses_category_assigned_to_users as category_expenses WHERE expenses.expense_category_assigned_to_user_id = category_expenses.id AND expenses.user_id= :id_user AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date AND category_expenses.name = :category_name ORDER BY date';

                $db_expenses_details = static::getDB();
                $stmt_expenses_details = $db_expenses_details -> prepare($sql_expenses_details);

                $stmt_expenses_details -> bindValue(':id_user', $logged_user_id, PDO::PARAM_INT);
                $stmt_expenses_details -> bindValue(':first_date', $firstDate, PDO::PARAM_STR);
                $stmt_expenses_details -> bindValue(':second_date', $secondDate, PDO::PARAM_STR);  
                $stmt_expenses_details -> bindValue(':category_name', $result_sum_of_expenses[0], PDO::PARAM_INT);   
                $stmt_expenses_details -> execute();

                $result_expenses_details = $stmt_expenses_details -> fetchAll();

                //var_dump($result_expenses_details);

                return $result_expenses_details;
            }
    }
/*

    array(4) { 
        [0]=> string(8) "Rozrywka" 
        [1]=> string(16) "Opieka zdrowotna" 
        [2]=> string(8) "Jedzenie" 
        [3]=> string(9) "Darowizna" }


        array(5) { 
            [0]=> array(8) { 
                ["category"]=> string(8) "Rozrywka" [0]=> string(8) "Rozrywka" ["date"]=> string(10) "2021-08-01" [1]=> string(10) "2021-08-01" ["comment"]=> string(5) "Sushi" [2]=> string(5) "Sushi" ["amount"]=> string(6) "120.00" [3]=> string(6) "120.00" } 
            [1]=> array(8) { 
                ["category"]=> string(9) "Darowizna" [0]=> string(9) "Darowizna" ["date"]=> string(10) "2021-08-01" [1]=> string(10) "2021-08-01" ["comment"]=> string(16) "Taca w kościele" [2]=> string(16) "Taca w kościele" ["amount"]=> string(5) "10.00" [3]=> string(5) "10.00" } 
            [2]=> array(8) { 
                ["category"]=> string(8) "Jedzenie" [0]=> string(8) "Jedzenie" ["date"]=> string(10) "2021-08-01" [1]=> string(10) "2021-08-01" ["comment"]=> string(6) "Banany" [2]=> string(6) "Banany" ["amount"]=> string(5) "22.00" [3]=> string(5) "22.00" } 
            [3]=> array(8) { 
                ["category"]=> string(8) "Rozrywka" [0]=> string(8) "Rozrywka" ["date"]=> string(10) "2021-08-01" [1]=> string(10) "2021-08-01" ["comment"]=> string(7) "Melanż" [2]=> string(7) "Melanż" ["amount"]=> string(7) "1000.00" [3]=> string(7) "1000.00" } 
            [4]=> array(8) { 
                ["category"]=> string(16) "Opieka zdrowotna" [0]=> string(16) "Opieka zdrowotna" ["date"]=> string(10) "2021-08-04" [1]=> string(10) "2021-08-04" ["comment"]=> string(4) "Leki" [2]=> string(4) "Leki" ["amount"]=> string(6) "121.00" [3]=> string(6) "121.00" } }





    /* get sum of expenses from database */
    public static function getSumOfExpenses($firstDate, $secondDate) 
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_expenses_sum = 'SELECT SUM(amount) FROM expenses WHERE user_id = :logged_user_id AND expenses.date_of_expense >= :first_date AND expenses.date_of_expense <= :second_date';

        $db_expenses_sum = static::getDB();
        $stmt_expenses_sum = $db_expenses_sum -> prepare($sql_expenses_sum);

        $stmt_expenses_sum -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);
        $stmt_expenses_sum -> bindValue(':first_date', $firstDate, PDO::PARAM_STR);
        $stmt_expenses_sum -> bindValue(':second_date', $secondDate, PDO::PARAM_STR);  
        $stmt_expenses_sum -> execute();
        $expenses_sum = $stmt_expenses_sum -> fetchColumn();

        return $expenses_sum;
    }



    public static function balanceAmount($firstDate, $secondDate) 
    {
        //save or no
        $incomes_sum = static::getSumOfIncomes($firstDate, $secondDate);
        $expenses_sum = static::getSumOfExpenses($firstDate, $secondDate);

        $balance = $incomes_sum - $expenses_sum;

        return $balance;       
    }
    


    public static function getBalanceFromCurrentMonth()
    {
        // first day of current month 
        $firstDate = new DateTime('first day of this month');        
        $firstDate = $firstDate -> format('Y-m-d');

        // last day of current month 
        $secondDate = new DateTime('last day of this month');
        $secondDate = $secondDate -> format('Y-m-d');

        $balance = static::balanceAmount($firstDate, $secondDate); 
        $incomes_sum = static::getSumOfIncomes($firstDate, $secondDate);
        $expenses_sum = static::getSumOfExpenses($firstDate, $secondDate); 
        
        return $information = static::saveOrNo($balance, $incomes_sum, $expenses_sum);
    }



    public function getBalanceFromPreviousMonth()
    {
        // first day of previous month 
        $firstDate = new DateTime('first day of previous month');  
        $firstDate = $firstDate -> format('Y-m-d');  

        // last day of previous month 
        $secondDate = new DateTime('last day of previous month');
        $secondDate = $secondDate -> format('Y-m-d');

        $balance = balanceAmount($firstDate, $secondDate);        
    }



    public function getBalanceFromSelectedDates($firstDate, $secondDate)
    {
        $balance = balanceAmount($firstDate, $secondDate);        
    }



    public static function saveOrNo($amount, $incomes_sum, $expenses_sum) 
    {
        if($amount > 0) $_SESSION['information'] = "Zaoszczędziłeś ".$amount." zł";
        else if($amount < 0){
        $amount = abs($incomes_sum - $expenses_sum);
        $_SESSION['information'] = "Twoje wydatki były o ".$amount." zł większe niż przychody";
        } 
        else if($amount == 0)$_SESSION['information'] = "Twoje wydatki były równe Twoim przychodom";

        return $_SESSION['information'];
    }

}
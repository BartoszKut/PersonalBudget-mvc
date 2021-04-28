<?php

namespace App\Models;

use PDO;

/* Example expense model */
class Expense extends \Core\Model
{

    /* error messages */
    public $errors = [];



    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }



    /* save the expense model */
    public function save()
    {
        $this -> validate();

        //if(empty($this -> errors)){
        if ($all_ok = true) {
            $id_user = $_SESSION['user_id'];

            // Get payment method id 
            $sql_payment_method = 'SELECT id FROM payment_methods_assigned_to_users WHERE name = :paymentmethod AND user_id = :user_id';

            $db_payment_method = static::getDB();
            $stmt_payment_method = $db_payment_method -> prepare($sql_payment_method);

            $stmt_payment_method -> bindValue(':paymentmethod', $this -> paymentmethod, PDO::PARAM_STR);
            $stmt_payment_method -> bindValue(':user_id', $id_user, PDO::PARAM_INT);

            $stmt_payment_method -> execute();

            $payment_method = $stmt_payment_method -> fetchColumn();



            // Get expense category id 
            $sql_expense_cat = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :expensecat AND user_id = :user_id';

            $db_expense_cat = static::getDB();
            $stmt_expense_cat = $db_expense_cat -> prepare($sql_expense_cat);

            $stmt_expense_cat -> bindValue(':expensecat', $this -> expensecat, PDO::PARAM_STR);
            $stmt_expense_cat -> bindValue(':user_id', $id_user, PDO::PARAM_INT);

            $stmt_expense_cat -> execute();

            $category_id = $stmt_expense_cat -> fetchColumn();


            // Add expense to database
            $sql_add_expense = 'INSERT INTO expenses (id, user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) VALUES (NULL, :user_id, :category_id, :paymentmethod, :amount, :date, :comment)';

            $db_add_expense = static::getDB();
            $stmt_add_expense = $db_add_expense -> prepare($sql_add_expense);

            $stmt_add_expense -> bindValue(':user_id', $id_user, PDO::PARAM_INT);
            $stmt_add_expense -> bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $stmt_add_expense -> bindValue(':paymentmethod', $payment_method, PDO::PARAM_STR);
            $stmt_add_expense -> bindValue(':amount', $this -> amount, PDO::PARAM_STR);
            $stmt_add_expense -> bindValue(':date', $this -> date, PDO::PARAM_STR);
            $stmt_add_expense -> bindValue(':comment', $this -> comment, PDO::PARAM_STR);

            $stmt_add_expense -> execute();
          
            return true;
        }
        return false;
    }



    /* validation */
    public function validate()
    {
        $all_ok = true;

        //amount correctness check        
        if((is_numeric($this -> amount) == false) || ($this -> amount < 0.01) || ($this -> amount > 2147483647)){
            //$this -> errors[] = "Podaj prawidłową wartość przychodu.";
            $_SESSION['error_amount'] = "Podaj prawidłową wartość przychodu";
            $all_ok = false;
        }


        //date correctness check
        $date = $this -> date;
        $Date = strtotime($date);    
        $timestamp = $Date; 
        $day=date('d',$timestamp);
        $month=date('m',$timestamp);
        $year=date('Y',$timestamp);
        if(!checkdate($month, $day, $year)){
            //$this -> errors[] = "Wprowadź poprawną datę.";
            $_SESSION['error_date'] = "Wprowadź poprawną datę";
            $all_ok = false;
        }


        //category correctness check
        if($this -> expensecat == ""){
            //$this -> errors[] = "Wybierz kategorię przychodu.";
            $_SESSION['error_category'] = "Wybierz kategorię przychodu";
            $all_ok = false;
        }   


        //paymnet method correctness check
        if($this -> paymentmethod == ""){
            //$this -> errors[] = "Wybierz rodzaj płatności.";
            $_SESSION['error_paymentmethod'] = "Wybierz rodzaj płatności";
            $all_ok = false;
        }   
    }
  

}
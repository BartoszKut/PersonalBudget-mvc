<?php

namespace App\Models;

use PDO;

/* Example income model */
class Income extends \Core\Model
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



    /* save the income model */
    public function save()
    {
        $all_ok = true;
        
        $this -> amountValidation();
        $this -> dateValidation();
        $this -> categoryValidation();

        //if(empty($this -> errors)){
        if ($all_ok = true) {

            $id_user = $_SESSION['user_id'];

            // Get income category id 
            $sql_income_cat = 'SELECT id FROM incomes_category_assigned_to_users WHERE name = :incomecat AND user_id = :user_id';

            $db_income_cat = static::getDB();
            $stmt_incomes_cat = $db_income_cat -> prepare($sql_income_cat);

            $stmt_incomes_cat -> bindValue(':incomecat', $this -> incomecat, PDO::PARAM_STR);
            $stmt_incomes_cat -> bindValue(':user_id', $id_user, PDO::PARAM_INT);

            $stmt_incomes_cat -> execute();

            $category_id = $stmt_incomes_cat -> fetchColumn();


            // Add income to database
            $sql_add_income = 'INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (NULL, :user_id, :category_id, :amount, :date, :comment)';

            $db_add_income = static::getDB();
            $stmt_add_income = $db_add_income -> prepare($sql_add_income);

            $stmt_add_income -> bindValue(':user_id', $id_user, PDO::PARAM_INT);
            $stmt_add_income -> bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $stmt_add_income -> bindValue(':amount', $this -> amount, PDO::PARAM_STR);
            $stmt_add_income -> bindValue(':date', $this -> date, PDO::PARAM_STR);
            $stmt_add_income -> bindValue(':comment', $this -> comment, PDO::PARAM_STR);

            $stmt_add_income -> execute();
          
            return true;
        }
        return false;
    }



    /* validation */
    public function amountValidation()
    {
        if((is_numeric($this -> amount) == false) || ($this -> amount < 0.01) || ($this -> amount > 2147483647)){
            $_SESSION['error_amount'] = "Podaj prawidłową wartość przychodu";
            $all_ok = false;
        }
    }



    public function dateValidation()
    {
        $date = $this -> date;
        $Date = strtotime($date);    
        $timestamp = $Date; 
        $day=date('d',$timestamp);
        $month=date('m',$timestamp);
        $year=date('Y',$timestamp);
        if(!checkdate($month, $day, $year)){
            $_SESSION['error_date'] = "Wprowadź poprawną datę";
            $all_ok = false;
        }
    }



    public function categoryValidation()
    {
        if($this -> incomecat == ""){
            //$this -> errors[] = "Wybierz kategorię przychodu.";
            $_SESSION['error_category'] = "Wybierz kategorię przychodu";
            $all_ok = false;
        }   
    }


    
    /* validation 
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
        if($this -> incomecat == ""){
            //$this -> errors[] = "Wybierz kategorię przychodu.";
            $_SESSION['error_category'] = "Wybierz kategorię przychodu";
            $all_ok = false;
        }   
    }*/
}
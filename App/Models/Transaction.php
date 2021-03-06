<?php

namespace App\Models;

use PDO;

/* Example transaction model */
class Transaction extends \Core\Model
{
    public static function amountValidation($amount)
    {
        if((is_numeric($amount) == false) || ($amount < 0.01) || ($amount > 2147483647)){
            $_SESSION['error_amount'] = "Podaj prawidłową wartość przychodu";
            $all_ok = false;
        }
    }



    public static function dateValidation($date)
    {
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



    public static function categoryValidation($category)
    {
        if($category == ""){
            $_SESSION['error_category'] = "Wybierz kategorię przychodu";
            $all_ok = false;
        }   
    }



    public static function newCategoryValidation($category)
    {
        if((strlen($category) < 3) || (strlen($category) > 20)){
            $_SESSION['newCategory_error'] = "Kategoria musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
    }



    public static function paymentMethodValidation($method) 
    {
        if($method == ""){
            $_SESSION['error_paymentmethod'] = "Wybierz rodzaj płatności";
            $all_ok = false;
        } 
    }



    public static function newPaymentMethodValidation($method) 
    {
        if((strlen($method) < 3) || (strlen($method) > 20)){
            $_SESSION['newMethod_error'] = "Sposób płatności musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
    }
}

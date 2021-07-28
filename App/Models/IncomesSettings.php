<?php

namespace App\Models;

use PDO;
use App\Models\Incomes;
use App\Auth;


class IncomesSettings extends Income
{
    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }



    public function updateIncomesData()
    {   
        $all_ok = true;
        $newIncomeCategory = $this -> newIncomeCategory;
        $categoryToDelete = $this -> categoryToDelete;

        $user_id = $_SESSION['user_id'];

        if($newIncomeCategory != "") {
            $this -> newCategoryValidation();

            if($all_ok == true) {
                $sql_new_incomes_cat = 'INSERT INTO incomes_category_assigned_to_users (id, user_id, name) VALUES (NULL, :user_id, :newIncomeCategory)';

                $db_new_incomes_cat = static::getDB();
                $stmt_new_incomes_cat = $db_new_incomes_cat -> prepare($sql_new_incomes_cat);
    
                $stmt_new_incomes_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_new_incomes_cat -> bindValue(':newIncomeCategory', $this -> newIncomeCategory, PDO::PARAM_STR);
    
                $stmt_new_incomes_cat -> execute();
              
                //return true;  
            }
            //return false;
        }

        if($categoryToDelete != "" && $categoryToDelete != "Wybierz kategorię") {
            $sql_delete_income_cat = 'DELETE FROM incomes_category_assigned_to_users WHERE name = :categoryToDelete AND user_id = :user_id';

            $db_delete_income_cat = static::getDB();
            $stmt_delete_income_cat = $db_delete_income_cat -> prepare($sql_delete_income_cat);

            $stmt_delete_income_cat -> bindValue(':categoryToDelete', $$this -> categoryToDelete, PDO::PARAM_STR);
            $stmt_delete_income_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt_delete_income_cat -> execute();
            
            //return true;  
        }
    }    
    
    

    public function newCategoryValidation()
    {
        if((strlen($this -> newIncomeCategory) < 3) || (strlen($this -> newIncomeCategory) > 20)){
            //$this -> errors[] = "Imię musi posiadać od 3 do 20 znaków!";
            $_SESSION['newIncomeCategory_error'] = "Kategoria musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
    }



    public static function getIncomesCategories()
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_select_incomes_categories = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :logged_user_id';

        $db_select_incomes_categories = static::getDB();
        $stmt_select_incomes_categories = $db_select_incomes_categories -> prepare($sql_select_incomes_categories);

        $stmt_select_incomes_categories -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);

        $stmt_select_incomes_categories -> execute();

        $result_select_incomes_categories = $stmt_select_incomes_categories -> fetchAll();

        return $result_select_incomes_categories;
    }
    
}

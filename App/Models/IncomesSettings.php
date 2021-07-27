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
    
                $stmt_add_income -> execute();
              
                return true;  
            }
            return false;
        }

        if($categoryToDelete != "" && $categoryToDelete == "Wybierz kategorię") {
            $sql_delete_income_cat = 'DELETE FROM incomes_category_assigned_to_users WHERE name = :categoryToDelete AND user_id = :user_id';

            $db_delete_income_cat = static::getDB();
            $stmt_delete_income_cat = $db_delete_income_cat -> prepare($sql_delete_income_cat);

            $stmt_delete_income_cat -> bindValue(':categoryToDelete', $$this -> categoryToDelete, PDO::PARAM_STR);
            $stmt_delete_income_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt_add_income -> execute();
            
            //return true;  
        }
    }    
    
    

    public function newCategoryValidation()
    {
        if((strlen($newIncomeCategory) < 3) || (strlen($newIncomeCategory) > 20)){
            //$this -> errors[] = "Imię musi posiadać od 3 do 20 znaków!";
            $_SESSION['newIncomeCategory_error'] = "Kategoria musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
    }
    
}

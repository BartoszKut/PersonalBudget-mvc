<?php

namespace App\Models;

use PDO;
use App\Models\Income;
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

        $arguments = Income::getIncomesCategories();

        $categoryInDatabase = in_array($this -> newIncomeCategory, $arguments);
        
        if($newIncomeCategory != "" && $categoryInDatabase == false) {
            Transaction::newCategoryValidation($this -> newIncomeCategory);          

            if($all_ok == true) {
                $sql_new_incomes_cat = 'INSERT INTO incomes_category_assigned_to_users (id, user_id, name) VALUES (NULL, :user_id, :newIncomeCategory)';

                $db_new_incomes_cat = static::getDB();
                $stmt_new_incomes_cat = $db_new_incomes_cat -> prepare($sql_new_incomes_cat);
    
                $stmt_new_incomes_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_new_incomes_cat -> bindValue(':newIncomeCategory', $this -> newIncomeCategory, PDO::PARAM_STR);
    
                $stmt_new_incomes_cat -> execute();
            }
        }

        if($categoryToDelete != "" && $categoryToDelete != "Wybierz kategorię") {
            $sql_delete_income_cat = 'DELETE FROM incomes_category_assigned_to_users WHERE name = :categoryToDelete AND user_id = :user_id';

            $db_delete_income_cat = static::getDB();
            $stmt_delete_income_cat = $db_delete_income_cat -> prepare($sql_delete_income_cat);

            $stmt_delete_income_cat -> bindValue(':categoryToDelete', $this -> categoryToDelete, PDO::PARAM_STR);
            $stmt_delete_income_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt_delete_income_cat -> execute();
        }
        return true;
    }    



    /*public function updateModalIncomesData()
    {   

        echo "Dociera do tej funkcji";

        $all_ok = true;

        $incomeCategory = $_POST['incomeCategory'];
        $user_id = $_SESSION['user_id'];

        $arguments = Income::getIncomesCategories();
        $categoryInDatabase = in_array($incomeCategory, $arguments);        
        
        if($incomeCategory != "" && $categoryInDatabase == false) {
            Transaction::newCategoryValidation($incomeCategory);          

            if($all_ok == true) {
                $sql_new_incomes_cat = 'INSERT INTO incomes_category_assigned_to_users (id, user_id, name) VALUES (NULL, :user_id, :incomeCategory)';

                $db_new_incomes_cat = static::getDB();
                $stmt_new_incomes_cat = $db_new_incomes_cat -> prepare($sql_new_incomes_cat);
    
                $stmt_new_incomes_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_new_incomes_cat -> bindValue(':incomeCategory', $incomeCategory, PDO::PARAM_STR);
    
                $stmt_new_incomes_cat -> execute();
            }
        }
        return true;
    }  */

}





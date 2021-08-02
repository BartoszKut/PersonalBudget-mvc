<?php

namespace App\Models;

use PDO;

/* Example income model */
class Income extends Transaction
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
        
        Transaction::amountValidation($this -> amount);
        Transaction::dateValidation($this -> date);
        Transaction::categoryValidation($this -> incomecat);

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



    public static function getIncomesCategories()
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_select_incomes_categories = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :logged_user_id';

        $db_select_incomes_categories = static::getDB();
        $stmt_select_incomes_categories = $db_select_incomes_categories -> prepare($sql_select_incomes_categories);

        $stmt_select_incomes_categories -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);

        $stmt_select_incomes_categories -> execute();

        $result_select_incomes_categories = $stmt_select_incomes_categories -> fetchAll(PDO::FETCH_COLUMN, 0);

        return $result_select_incomes_categories;
    }
}
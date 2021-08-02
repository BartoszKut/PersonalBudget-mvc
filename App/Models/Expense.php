<?php

namespace App\Models;

use PDO;

/* Example expense model */
class Expense extends Transaction
{

    /* error messages */
    //public $errors = []; errores in $_SESSION[];



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
        $all_ok = true;
        
        Transaction::amountValidation($this -> amount);
        Transaction::dateValidation($this -> date);
        Transaction::categoryValidation($this -> expensecat);
        Transaction::paymentMethodValidation($this -> paymentmethod);

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



    public static function getExpensesCategories()
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_select_expenses_categories = 'SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :logged_user_id';

        $db_select_expenses_categories = static::getDB();
        $stmt_select_expenses_categories = $db_select_expenses_categories -> prepare($sql_select_expenses_categories);

        $stmt_select_expenses_categories -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);

        $stmt_select_expenses_categories -> execute();

        $result_select_expenses_categories = $stmt_select_expenses_categories -> fetchAll(PDO::FETCH_COLUMN, 0);

        return $result_select_expenses_categories;
    }



    public static function getPaymentMethods()
    {
        $logged_user_id = $_SESSION['user_id'];

        $sql_select_payment_methods = 'SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :logged_user_id';

        $db_select_payment_methods = static::getDB();
        $stmt_select_payment_methods = $db_select_payment_methods -> prepare($sql_select_payment_methods);

        $stmt_select_payment_methods -> bindValue(':logged_user_id', $logged_user_id, PDO::PARAM_INT);

        $stmt_select_payment_methods -> execute();

        $result_select_payment_methods = $stmt_select_payment_methods -> fetchAll(PDO::FETCH_COLUMN, 0);

        return $result_select_payment_methods;
    }
}
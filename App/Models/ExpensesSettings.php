<?php

namespace App\Models;

use PDO;
use App\Models\Expense;
use App\Auth;


class ExpensesSettings extends Expense
{
    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }



    public function updateExpensesData()
    {   
        $all_ok = true;
        $newExpenseCategory = $this -> newExpenseCategory;
        $categoryToDelete = $this -> categoryToDelete;
        $expenseLimitCategory = $this -> expenseLimitCategory;
        $expenseLimit = $this -> expenseLimit;
        $newPaymentMethod = $this -> newPaymentMethod;
        $paymentMethodToDelete = $this -> paymentMethodToDelete;

        $user_id = $_SESSION['user_id'];

        $categories = Expense::getExpensesCategories();
        $paymentMethods = Expense::getPaymentMethods();

        $categoryInDatabase = in_array($this -> newExpenseCategory, $categories);

        $paymentsMethodsInDatabase = in_array($this -> newPaymentMethod, $paymentMethods);
        
        if($newExpenseCategory != "" && $categoryInDatabase == false) {
            
            Transaction::newPaymentMethodValidation($this -> newExpenseCategory);

            if($all_ok == true) {
                $sql_new_expenses_cat = 'INSERT INTO expenses_category_assigned_to_users (id, user_id, name) VALUES (NULL, :user_id, :newExpenseCategory)';

                $db_new_expenses_cat = static::getDB();
                $stmt_new_expenses_cat = $db_new_expenses_cat -> prepare($sql_new_expenses_cat);
    
                $stmt_new_expenses_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_new_expenses_cat -> bindValue(':newExpenseCategory', $this -> newExpenseCategory, PDO::PARAM_STR);
    
                $stmt_new_expenses_cat -> execute();
            }
        }

        if($categoryToDelete != "" && $categoryToDelete != "Wybierz kategorię") {
            $sql_delete_expense_cat = 'DELETE FROM expenses_category_assigned_to_users WHERE name = :categoryToDelete AND user_id = :user_id';

            $db_delete_expense_cat = static::getDB();
            $stmt_delete_expense_cat = $db_delete_expense_cat -> prepare($sql_delete_expense_cat);

            $stmt_delete_expense_cat -> bindValue(':categoryToDelete', $this -> categoryToDelete, PDO::PARAM_STR);
            $stmt_delete_expense_cat -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt_delete_expense_cat -> execute();
        }

        if($expenseLimitCategory != "" && $expenseLimitCategory != "Wybierz kategorię" && $expenseLimit != "") {

            Transaction::amountValidation($this -> expenseLimit);

            $sql_add_expense_limit = 'UPDATE expenses_category_assigned_to_users SET month_limit = :month_limit WHERE user_id = :user_id AND name = :expenseLimitCategory';

            $db_add_expense_limit = static::getDB();
            $stmt_add_expense_limit = $db_add_expense_limit -> prepare($sql_add_expense_limit);

            $stmt_add_expense_limit -> bindValue(':month_limit', $this -> expenseLimit, PDO::PARAM_STR);
            $stmt_add_expense_limit -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_add_expense_limit -> bindValue(':expenseLimitCategory', $this -> expenseLimitCategory, PDO::PARAM_STR);            

            $stmt_add_expense_limit -> execute();
        }

        if($newPaymentMethod != "" && $paymentsMethodsInDatabase == false) {

            Transaction::newPaymentMethodValidation($this -> newPaymentMethod);     

            if($all_ok == true) {
                $sql_new_payment_method = 'INSERT INTO payment_methods_assigned_to_users (id, user_id, name) VALUES (NULL, :user_id, :newPaymentMethod)';

                $db_new_payment_method = static::getDB();
                $stmt_new_payment_method = $db_new_payment_method -> prepare($sql_new_payment_method);
    
                $stmt_new_payment_method -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt_new_payment_method -> bindValue(':newPaymentMethod', $this -> newPaymentMethod, PDO::PARAM_STR);
    
                $stmt_new_payment_method -> execute();
            }
        }

        if($paymentMethodToDelete != "" && $paymentMethodToDelete != "Wybierz kategorię") {
            $sql_delete_payment_method = 'DELETE FROM payment_methods_assigned_to_users WHERE name = :paymentMethodToDelete AND user_id = :user_id';

            $db_delete_payment_method = static::getDB();
            $stmt_delete_payment_method = $db_delete_payment_method -> prepare($sql_delete_payment_method);

            $stmt_delete_payment_method -> bindValue(':paymentMethodToDelete', $this -> paymentMethodToDelete, PDO::PARAM_STR);
            $stmt_delete_payment_method -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt_delete_payment_method -> execute();
        }
        return true;
    }     
    
}

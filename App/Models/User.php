<?php

namespace App\Models;

use PDO;

/* Example user model */
class User extends \Core\Model
{
    /* error messages */
    public $errors = []; //it is not necessary, because i decided to use $_SESSION validation with all_ok flag

    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }


    /* save the user model */
    public function save()
    {
        $password_hash = password_hash($this -> password, PASSWORD_DEFAULT);

        // Add user to database
        $sql = 'INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password_hash)';

        $db = static::getDB();
        $stmt = $db -> prepare($sql);

        $stmt -> bindValue(':name', $this -> name, PDO::PARAM_STR);
        $stmt -> bindValue(':surname', $this -> surname, PDO::PARAM_STR);
        $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
        $stmt -> bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

        $stmt -> execute();


        // Add categories of incomes to asigned_to_user table
        $sql_incomes_cat = 'INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT users.id, incomes_category_default.name FROM users, incomes_category_default WHERE users.email= :email';

        $db_incomes = static::getDB();
        $stmt_incomes_cat = $db_incomes -> prepare($sql_incomes_cat);

        $stmt_incomes_cat -> bindValue(':email', $this -> email, PDO::PARAM_STR);

        $stmt_incomes_cat -> execute();


        // Add categories of expenses to asigned_to_user table 
        $sql_expenses_cat = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT users.id, expenses_category_default.name FROM users, expenses_category_default WHERE users.email= :email';

        $db_expenses = static::getDB();
        $stmt_expenses_cat = $db_expenses -> prepare($sql_expenses_cat);

        $stmt_expenses_cat -> bindValue(':email', $this -> email, PDO::PARAM_STR);

        $stmt_expenses_cat -> execute();
        

        // Add categories of payment methods to asigned_to_user table
        $sql_pay_methods_cat = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT users.id, payment_methods_default.name FROM users, payment_methods_default WHERE users.email= :email';

        $db_pay_methods = static::getDB();
        $stmt_pay_methods_cat = $db_pay_methods -> prepare($sql_pay_methods_cat);

        $stmt_pay_methods_cat -> bindValue(':email', $this -> email, PDO::PARAM_STR);

        $stmt_pay_methods_cat -> execute();
    }


    /* validation methods */
    public static function nameValidation($name)
    {
        if((strlen($name) < 3) || (strlen($name) > 20)){
            return false;
        }
        if(!ctype_alpha($name)) {
            return false;
        }
        return true;
    }


    public static function surnameValidation($surname)
    {
        if((strlen($surname) < 3) || (strlen($surname) > 20)){ 
           return false;
        }
        if(ctype_alpha($surname) == false){
            return false;
        }
        return true;
    }


    public static function emailValidation($email)
    {
        $safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($safe_email, FILTER_VALIDATE_EMAIL)==false) || ($safe_email != $email)){
            return false;
        }

        if(static::emailExist($email)){
            return false;
        }
        return true;
    }


    public static function passwordValidation($password, $password2)
    {   
        if((strlen($password) < 6) || (strlen($password) > 20)){ 
            return false;
        }
        if($password != $password2){ 
            return false;
        }
        return true;
    }


    /* Check is email exists */
    public static function emailExist($email)
    {
        return static::findByEmail($email) !== false;
    }


    /* Find user in database by email */
    public static function findByEmail($email)
    {
        $emailExisting = 'SELECT * FROM users WHERE email = :email';

        $db_check_email = static::getDB();
        $stmt_check_email = $db_check_email -> prepare($emailExisting);

        $stmt_check_email -> bindValue(':email', $email, PDO::PARAM_STR);

        $stmt_check_email -> setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt_check_email -> execute();

        return $stmt_check_email -> fetch();
    }


    /* Find user in database by user_id */
    public static function findById($user_id)
    {
        $idExisting = 'SELECT * FROM users WHERE id = :user_id';

        $db_check_id = static::getDB();
        $stmt_check_id = $db_check_id -> prepare($idExisting);

        $stmt_check_id -> bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt_check_id -> setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt_check_id -> execute();

        return $stmt_check_id -> fetch();
    }


    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if($user) {
            if (password_verify($password, $user -> password)) {
                return $user;
            }
        } else {
            return null;
        }
    }
}

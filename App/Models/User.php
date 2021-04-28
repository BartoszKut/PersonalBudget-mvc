<?php

namespace App\Models;

use PDO;

/* Example user model */
class User extends \Core\Model
{

    /* error messages */
    //public $errors = []; it is not necessary, because i decided to use $_SESSION validation with all_ok flag



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
        $this -> validate();

        //if(empty($this -> errors)){
        if ($all_ok = true) {
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

            return true;
        }
        return false;
    }



    /* validation */
    public function validate()
    {   
        $all_ok = true;

        // Name
        if((strlen($this -> name) < 3) || (strlen($this -> name) > 20)){
            //$this -> errors[] = "Imię musi posiadać od 3 do 20 znaków!";
            $_SESSION['name_error'] = "Imię musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
        if(ctype_alpha($this -> name) == false){
            //$this -> errors[] = "Imię może składać się tylko z liter! (bez polskich znaków)";
            $_SESSION['name_error'] = "Imię może składać się tylko z liter! (bez polskich znaków)";
            $all_ok = false;
        }


        // Surname
        if((strlen($this -> surname) < 3) || (strlen($this -> surname) > 20)){ 
            //$this -> errors[] = "Nazwisko musi posiadać od 3 do 20 znaków!";
            $_SESSION['surname_error'] = "Nazwisko musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
        if(ctype_alpha($this -> surname) == false){
            //$this -> errors[] = "Nazwisko może składać się tylko z liter! (bez polskich znaków)";
            $_SESSION['surname_error'] = "Nazwisko może składać się tylko z liter! (bez polskich znaków)";
            $all_ok = false;
        }


        // Email
        $safe_email = filter_var($this -> email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($safe_email, FILTER_VALIDATE_EMAIL)==false) || ($safe_email != $this -> email)){
            //$this -> errors[] = "Podaj poprawny adres email!";
            $_SESSION['email_error'] = "Podaj poprawny adres email!";
            $all_ok = false;
        }

        if(static::emailExist($this -> email)){
            //$this -> errors[] = "Istnieje już konto przypisane do tego e-maila!";
            $_SESSION['email_error'] = "Istnieje już konto przypisane do tego e-maila!";
            $all_ok = false;
        }


        // Password
        if((strlen($this -> password) < 6) || (strlen($this -> password) > 20)){ 
            //$this -> errors[] = "Hasło musi posiadać od 6 do 20 znaków!";
            $_SESSION['password_error'] = "Hasło musi posiadać od 6 do 20 znaków!";
            $all_ok = false;
        }
        if($this -> password != $this -> password2){ 
            //$this -> errors[] = "Hasła muszą być identyczne!";
            $_SESSION['password_error'] = "Hasła muszą być identyczne!";
            $all_ok = false;
        }
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
        }
        return false;
    }



    

}

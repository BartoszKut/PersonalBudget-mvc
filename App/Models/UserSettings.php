<?php

namespace App\Models;

use PDO;
use App\Models\User;
use App\Auth;

/* Example income model */
class UserSettings extends User
{

    /* constructor */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this -> $key = $value;
        };
    }


    /* save changes of user data */
    public function updateUserData()
    {
        $this -> validateDataBeforeChanges();

        if($all_ok = true) {

            // Update user
            $sql = 'UPDATE users SET name=:name, surname=:surname, email=:email, /*password=:password*/ WHERE id=:id)';

            $db = static::getDB();
            $stmt = $db -> prepare($sql);

            $stmt -> bindValue(':name', $this -> name, PDO::PARAM_STR);
            $stmt -> bindValue(':surname', $this -> surname, PDO::PARAM_STR);
            $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
            /*$stmt -> bindValue(':password', $this -> password, PDO::PARAM_STR);*/
            $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

            $stmt -> execute();

            return true;
        }
        return false;
    }


    /*public function updateUserInDatabase(){

        $this -> validateDataWithoutPassword();

        //if(empty($this -> errors)){
        if ($all_ok = true) {

            // Update user
            $sql = 'UPDATE users SET name=:name, surname=:surname, email=:email WHERE id=:id)';

            $db = static::getDB();
            $stmt = $db -> prepare($sql);

            $stmt -> bindValue(':name', $this -> name, PDO::PARAM_STR);
            $stmt -> bindValue(':surname', $this -> surname, PDO::PARAM_STR);
            $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
            $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

            $stmt -> execute();

            return true;
        }
        return false;
    }*/



    public function validateDataBeforeChanges()
    {   
        $all_ok = true;
        $name = $this -> name;
        $surname = $this -> surname;
        $email = $this -> email;
        $password = $this -> password;
        $password2 = $this -> password2;
        
        //Auth::getUser();

        if($name != "" && $name != $_SESSION['user_name']) {
            $name -> parent::nameValidation();
        } else {
            $name = $_SESSION['user_name'];
        }

        if($surname != "" && $surname != $_SESSION['user_surname']) {
            $surname -> parent::surnameValidation();
        } else {
            $surname = $_SESSION['user_surname'];
        }

        if($email != "" && $email != $_SESSION['user_email']) {
            $email -> parent::emailValidation();
        } else {
            $email = $_SESSION['user_email'];
        }

        if($password != "" && $password != $_SESSION['user_password']) {
            $password -> parent::passwordValidation();
        } else {
            $password = $_SESSION['user_password'];
        }
    }

        
        /*if((strlen($name) < 3) || (strlen($name) > 20)){
            //$this -> errors[] = "Imię musi posiadać od 3 do 20 znaków!";
            $_SESSION['name_error'] = "Imię musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
        if(ctype_alpha($name) == false){
            //$this -> errors[] = "Imię może składać się tylko z liter! (bez polskich znaków)";
            $_SESSION['name_error'] = "Imię może składać się tylko z liter! (bez polskich znaków)";
            $all_ok = false;
        }


        // Surname
        if((strlen($surname) < 3) || (strlen($surname) > 20)){ 
            //$this -> errors[] = "Nazwisko musi posiadać od 3 do 20 znaków!";
            $_SESSION['surname_error'] = "Nazwisko musi posiadać od 3 do 20 znaków!";
            $all_ok = false;
        }
        if(ctype_alpha($surname) == false){
            //$this -> errors[] = "Nazwisko może składać się tylko z liter! (bez polskich znaków)";
            $_SESSION['surname_error'] = "Nazwisko może składać się tylko z liter! (bez polskich znaków)";
            $all_ok = false;
        }


        // Email
        $safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($safe_email, FILTER_VALIDATE_EMAIL)==false) || ($safe_email != $email)){
            //$this -> errors[] = "Podaj poprawny adres email!";
            $_SESSION['email_error'] = "Podaj poprawny adres email!";
            $all_ok = false;
        }

        if(static::emailExist($email)){
            //$this -> errors[] = "Istnieje już konto przypisane do tego e-maila!";
            $_SESSION['email_error'] = "Istnieje już konto przypisane do tego e-maila!";
            $all_ok = false;
        } 
    }



    public function newPassword(){
        // Password
        if($password == "") {
            return false;
        } else {
            if((strlen($password) < 6) || (strlen($password) > 20)){ 
                //$this -> errors[] = "Hasło musi posiadać od 6 do 20 znaków!";
                $_SESSION['password_error'] = "Hasło musi posiadać od 6 do 20 znaków!";
                return false;
            }
            if($password != $password2){ 
                //$this -> errors[] = "Hasła muszą być identyczne!";
                $_SESSION['password_error'] = "Hasła muszą być identyczne!";
                return false;
            }
            return true;
        }
    }
    */




     
}
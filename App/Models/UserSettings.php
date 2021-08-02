<?php

namespace App\Models;

use PDO;
use App\Models\User;  
use App\Auth;


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
        $all_ok = true;

        $this -> validateDataBeforeChanges();

        // update without password
        if($this -> password == "" && $all_ok == true) {

            $sql = 'UPDATE users SET name=:name, surname=:surname, email=:email WHERE id=:id';

            $db = static::getDB();
            $stmt = $db -> prepare($sql);

            $stmt -> bindValue(':name', $this -> name, PDO::PARAM_STR);
            $stmt -> bindValue(':surname', $this -> surname, PDO::PARAM_STR);
            $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
            $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

            $stmt -> execute();

            return true;
        }
        // update with password
        else if($all_ok == true) {
            $password_hash = password_hash($this -> password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users SET name=:name, surname=:surname, email=:email, password=:password_hash WHERE id=:id';

            $db = static::getDB();
            $stmt = $db -> prepare($sql);

            $stmt -> bindValue(':name', $this -> name, PDO::PARAM_STR);
            $stmt -> bindValue(':surname', $this -> surname, PDO::PARAM_STR);
            $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
            $stmt -> bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

            $stmt -> execute();

            return true;
        }
        return false;
    }



    public function validateDataBeforeChanges()
    {   
        $all_ok = true;
        $name = $this -> name;
        $surname = $this -> surname;
        $email = $this -> email;
        $password = $this -> password;
        $password2 = $this -> password2;

        if($name != "" && $name != $_SESSION['user_name']) {
            User::nameValidation($this -> name);
        } else {
            $name = $_SESSION['user_name'];
        }

        if($surname != "" && $surname != $_SESSION['user_surname']) {
            User::surnameValidation($this -> surname);
        } else {
            $surname = $_SESSION['user_surname'];
        }

        if($email != "" && $email != $_SESSION['user_email']) {
            User::emailValidation($this -> email);
        } else {
            $email = $_SESSION['user_email'];
        }

        if($password != "") {
            User::passwordValidation($this -> password, $this -> password2);
        }
    }     
}
<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/* Signup controller */
class Signup extends \Core\Controller
{

    /* Show the signup page */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }


    /* Sign up new user */
    public function createAction()
    {
        $errors = [];

        if(!User::nameValidation($_POST['name'])) {
            $errors['name'] = "Niepoprawne imię!";
        }
        if(!User::surnameValidation($_POST['surname'])) {
            $errors['surname'] = "Niepoprawne nazwisko!";
        }
        if(!User::emailValidation($_POST['email'])) {
            $errors['email'] = "Podany adres email jest już używany na innym koncie!";
        }
        if(!User::passwordValidation($_POST['password'], $_POST['password2'])) {
            $errors['password'] = "Podane hasła nie są identyczne! (hasło musi zawierać od 6 do 20 znaków!";
        }

        if (User::nameValidation($_POST['name']) &&
            User::surnameValidation($_POST['surname']) &&
            User::emailValidation($_POST['email']) &&
            User::passwordValidation($_POST['password'], $_POST['password2'])) {
                $user = new User($_POST);
                $user -> save();
                $this -> redirect('/signup/success');
        } else {
            View::renderTemplate('Signup/new.html', $errors);
        }
    }


    public function successAction()
    {
        View::renderTemplate('Signup/welcome.html');
    }
}
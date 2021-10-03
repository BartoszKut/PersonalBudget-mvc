<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;


/* Login controller */

class Login extends \Core\Controller
{
     /* error messages */
    public $errors = []; //errores in $_SESSION[];

    /* Show the login page */
    public function newAction()
    {
        if (Auth::getUser() != null) {
            $this -> redirect('/items/index');
        } else {
            View::renderTemplate('Login/new.html');
        }        
    }


    /* Log in user */
    public function createAction()
    {
        if(isset($_POST['email']) && isset($_POST['password'])) {
            $user = User::authenticate($_POST['email'], $_POST['password']);
        } else {
            return $this -> redirect('/login');
        }
        
        if($user != null) {
            Auth::login($user);           
            View::renderTemplate('LoggedIn/mainMenu.html');
        } else {
            $errors['login'] = "Niepoprawny login lub hasło!";
            View::renderTemplate('Login/new.html', $errors, [
                'email' => $_POST['email'],
            ]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('LoggedIn/mainMenu.html');
    }


    // Logout user
    public function destroyAction()
    {
        Auth::logout();

        $info = [];
        $info['logout'] = "Wylogowano poprawnie.";

        View::renderTemplate('Home/index.html', $info);
    }
}
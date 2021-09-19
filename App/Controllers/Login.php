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
        //if (isset($_SESSION['user_id'])) 
        if (Auth::getUser()) {

            $this -> redirect('/items/index');

        } else {
            
            View::renderTemplate('Login/new.html');
        }        
    }



    /* Log in user */
    public function createAction()
    {
        if(isset($_POST['email'])) {
            $user = User::authenticate($_POST['email'], $_POST['password']);
        } else {
            return $this -> redirect('/login');
        }
        
        if($user) {
            Auth::login($user);           

            $_SESSION['return_to'] = '/login/success';
            
            $this -> redirect(Auth::getReturnToPage());
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



    // // Show logged out flash message
    // public function showLogoutMessageAction()
    // {
    //     Flash::addMessage('Wylogowano poprawnie!');
    //     echo "chujek";

    //     $this -> redirect('/');
    // }

}
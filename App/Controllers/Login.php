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
    //public $errors = []; //errores in $_SESSION[];

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
        $user = User::authenticate($_POST['email'], $_POST['password']);
        //var_dump($_SESSION['error_passOrEmail']);

        if ($user) {

            Auth::login($user);           

            $_SESSION['return_to'] = '/login/success';
            //$this -> redirect('/PersonalBudget-mvc/public/login/success');
            $this -> redirect(Auth::getReturnToPage());

        } else {

            View::renderTemplate('Login/new.html', [
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

        $this -> redirect('/login/show-logout-message');
    }



    // Show logged out flash message
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Wylogowano poprawnie!');

        $this -> redirect('/');
    }

}
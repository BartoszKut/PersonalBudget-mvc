<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;


/* Login controller */

class Login extends \Core\Controller
{

    /* Show the login page */
    public function newAction()
    {
        //if (isset($_SESSION['user_id'])) 
        if (Auth::getUser()) {

            $this -> redirect('/PersonalBudget-mvc/public/items/index');

        } else {

            View::renderTemplate('Login/new.html');
        }        
    }



    /* Log in user */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        if ($user) {

            Auth::login($user);

            $_SESSION['return_to'] = '/PersonalBudget-mvc/public/login/success';
            //$this -> redirect('/PersonalBudget-mvc/public/login/success');
            $this -> redirect(Auth::getReturnToPage());

        } else {

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email']
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

        $this -> redirect('/PersonalBudget-mvc/public/login/show-logout-message');
    }



    // Show logged out flash message
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Wylogowano poprawnie!');

        $this -> redirect('/PersonalBudget-mvc/public');
    }

}
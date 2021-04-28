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
        $user = new User($_POST);
 
        if ($user -> save()){
            $this -> redirect('/PersonalBudget-mvc/public/signup/success');
        } else {
            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
        }
    }



    public function successAction()
    {
        View::renderTemplate('Signup/welcome.html');
    }
}

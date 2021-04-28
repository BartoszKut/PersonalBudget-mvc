<?php

namespace App;

use \App\Models\User;


/* Authentication */
class Auth
{

    /* Log in the user */
    public static function login($user)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user -> id;
    }



    /* Log out user */
    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }



    /* Return indicator of whether a user is logged in or not */
    //getUser method does same things
    /*
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }*/


    
    /* Remember the originally-requested page in the session */
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }



    /* Return to requested page after successfully log in */
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }



    /* Get the current logged-in user */
    public static function getUser()
    {
        if(isset($_SESSION['user_id'])) {
            return User::findById($_SESSION['user_id']);
        }
    }
}
<?php

namespace App\Controllers;

use \App\Models\User;

/* Account controller */
class Account extends \Core\Controller
{

    /* Validate if email is available (AJAX for new signup) */
    public function validateEmailAction()
    {
        $is_valid =! USER::emailExist($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}

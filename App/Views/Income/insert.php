<?php

$connect = mysqli_connect("localhost", "bartosz5_personalBudgetMVCAdmin", "", "bartosz5_personalBudgetMVC");

if(!empty($_POST))
{
    $output = '';
    $user_id = $_SESSION['user_id'];
    $incomeCategory = mysqli_real_escape_string($connect, $_POST["incomeCategory"]);

    $query = "INSERT INTO incomes_category_assigned_to_users (id, user_id, name) VALUES (NULL, '$user_id', '$incomeCategory')";

    if(mysqli_query($connect, $query))
    {
        $output .= '<label class="text-success">Data Inserted</label>';
    }

    echo $output;
}
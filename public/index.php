<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Sessions
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('addincome', ['controller' => 'Incomes', 'action' => 'new']);
$router->add('addexpense', ['controller' => 'Expenses', 'action' => 'new']);
$router->add('settings', ['controller' => 'AppSettings', 'action' => 'index']);
$router->add('incomeSettings', ['controller' => 'IncomeSettings', 'action' => 'new']);
$router->add('expenseSettings', ['controller' => 'ExpenseSettings', 'action' => 'new']);
$router->add('userSettings', ['controller' => 'UserSetting', 'action' => 'new']);
$router->add('currentMonthBalance', ['controller' => 'Balances', 'action' => 'current']);
$router->add('previousMonthBalance', ['controller' => 'Balances', 'action' => 'previous']);
$router->add('selectdates', ['controller' => 'Balances', 'action' => 'selectdates']);
$router->add('addexpense/{category:.+}', ['controller' => 'Expenses', 'action' => 'getCategoryLimit']);


$router->add('{controller}/{action}');
    
$router->dispatch($_SERVER['QUERY_STRING']);

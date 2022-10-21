<?php

require_once 'config.php';

$pdo = pdo_connect_mysql();

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function pdo_connect_mysql()
{
    try {
        return new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USER, DATABASE_PASS);
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function __($string)
{
    return $string;
}




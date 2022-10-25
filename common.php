<?php

require_once 'config.php';

$pdo = pdoConnectMysqli();

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

function pdoConnectMysqli()
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
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function __($string)
{
    return $string;
}




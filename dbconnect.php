<?php
/**
 * User: Roy Sinclair
 * Date: 2016/05/09
 * Time: 12:43 PM
 */

// Database Settings for PDO
if($ENV == 'SANDBOX') {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "push_notifications";
}

if ($ENV == 'PRODUCTION') {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "push_notifications";
}

$DB = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
try {
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e) {
    //show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}



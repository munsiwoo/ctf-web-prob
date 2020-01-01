<?php
error_reporting(0);
session_start();
require_once 'function.php';

$flag = "FLAG{here}"; // secret data
$admin_pw = "admin_pass"; // secret data

$host = 'localhost';
$user = 'admin';
$pass = 'password';

if(!isset($_SESSION['dbname'])) {
    $dbname = create_db($host, $user, $pass, md5(random_bytes(20)));
    $db = new mysqli($host, $user, $pass, $dbname);
    if($db) $_SESSION['dbname'] = $dbname;
}
else {
    $db = new mysqli($host, $user, $pass, $_SESSION['dbname']);
}
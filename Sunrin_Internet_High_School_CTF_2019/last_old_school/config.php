<?php
error_reporting(0);

if(isset($_GET['pw'])) { # payload log file
	file_put_contents('log-9e3078ac7b', $_GET['pw'].PHP_EOL, FILE_APPEND);
}

$conn = mysqli_connect('localhost', 'admin', 'password', 'test');
$FLAG = 'SUNRIN{dhdndickawkfgotdjdydrnt}';

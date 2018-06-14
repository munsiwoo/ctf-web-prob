<?php
error_reporting(0);
require_once 'control.php';
// goodaegi board

if(empty($_GET['p'])) header('Location:?p=home.html');
$page = secure_page($_GET['p']);

include_once 'header.php';
new Control($page, $_SERVER['REQUEST_METHOD']);
include_once 'footer.php';

<?php
error_reporting(0);
session_start();
# made by munsiwoo

if(empty($_GET['p'])) {
	header('Location: /home'); exit;
}

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config/function.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Controller.class.php';

if($page = secure_page($_GET['p'])) {
	$method = $_SERVER['REQUEST_METHOD'];
	$session = isset($_SESSION['username']);
	$Controller = new Controller($method, $page, $session);
}
else {
	header("HTTP/1.0 404 Not Found");
	die('The website is not exist');
}

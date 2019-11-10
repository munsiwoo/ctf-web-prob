<?php
error_reporting(0);
session_name('aleph-sessid');
session_start();
# made by munsiwoo

require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/function.php';
require_once __DIR__.'/classes/Controller.class.php';

$http_method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$is_login = isset($_SESSION['username']);

$Controller = new Controller($http_method, $request_uri, $is_login);

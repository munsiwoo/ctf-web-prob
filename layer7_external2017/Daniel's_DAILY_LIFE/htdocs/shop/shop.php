<?php
error_reporting(0);
session_start();
include '../include/library.php';

if(!isset($_SESSION['username'])){
	exit('first login');
}

if(token_chk($_SESSION['username'], $_SESSION['token']) === 0){
	exit('token error');
}

echo '<h1>Congratulation!!!!</h1>flag is {dlanswpsdjEoTskdy?wharneorlduTdma?}';
?>
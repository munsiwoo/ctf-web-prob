<?php
error_reporting(0);
session_start();
include 'admin_library.php';

if($_SESSION['username'] !== 'admin') exit("login first");
if($_SERVER['REMOTE_ADDR'] !== '172.17.0.1') exit("<script>location.href='../';</script>");

contacts_read();
?>
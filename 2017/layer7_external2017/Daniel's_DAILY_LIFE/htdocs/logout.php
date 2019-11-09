<?php
error_reporting(0);
session_start();
session_destroy();
$_SESSION['username'] = 'asdasdasdasdasdasdasdasdasd';
exit("<script>location.href='home.php';</script>");
?>
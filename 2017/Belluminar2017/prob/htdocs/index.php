<?php
error_reporting(0);
require("./page/config.php");
extract($_GET);

if(!isset($page)) die("<script>location.href='?page=login'</script>");
if(!file_exists($page.".php")) echo "file not found.\n";
if(!preg_match("/page/i", $page)) require($page.".php");
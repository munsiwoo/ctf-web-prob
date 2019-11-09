<?php
error_reporting(0);
include 'config.php';
/*  Last old school challenge!
    Can you solve it?  */

$filter_keyword = "_|[.]|sleep|bench|if|case|when|co|select|user";
if(preg_match("/{$filter_keyword}/i", $_GET['pw'])) {
	header('HTTP/1.1 403 Forbidden');
	die('hacking hazimaseyo!');
}

$query = "select id from user where id='admin' and pw='{$_GET['pw']}'";
mysqli_query($conn, $query) or die('mysql error');
echo "<b>query</b> : {$query}<hr>";

$_GET['pw'] = addslashes($_GET['pw']);
$query = "select pw from user where id='admin' and pw='{$_GET['pw']}';";
$fetch = mysqli_fetch_assoc(mysqli_query($conn, $query));

if($fetch['pw'] === $_GET['pw']) { // Do you know the password for admin?
	die("<h1>{$FLAG}</h1>");
}

show_source(__FILE__);


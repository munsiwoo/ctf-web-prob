<?php
error_reporting(0);
require_once 'config.php';

/*************************

 made by munsiwoo
 sqlgame-revenge

 sqlgame_revenge table structure :
 create table sqlgame_revenge (
	 username varchar(100),
	 password varchar(100)
 ) default charset=utf8;

**************************/

isset($_GET['username'], $_GET['password']) or view_source();

$conn = mysqli_connect('', __USER__, __PASS__, 'hahahoho');
$conn or die('sql server down.');

$username = substr($_GET['username'], 0, 400);
$password = substr($_GET['password'], 0, 400);

$filter  = "'|\"|x|b|y|substr|left|right|char|hex|md|sha|compress|mysql|blog";
$filter .= "|=|like|regexp|if|case|sleep|benchmark|munsiwoo|user|where|strcmp";
$filter .= "|column|table|load|file|view|sys|global|rand|innodb|space|mid|name";

(!preg_match("/{$filter}/is", $username.chr(32).$password)) or die('403 forbidden.');
(!preg_match("/password/i", $username)) or die('403 forbidden.');
(!(substr_count($username.$password, '(') > 25)) or die('403 forbidden.');

$query = "select * from sqlgame_revenge where username='{$username}' and password='{$password}'";
$query = mysqli_query($conn, $query) or die('syntax error.');
$fetch = mysqli_fetch_assoc($query) or die('user not found.');
$fetch = array_map('strtolower', $fetch);

(strtolower($fetch['username']) == 'munsiwoo') or die('who are you?');
(strtolower($fetch['password']) ==  $password) or die('pw incorrect!');

show_flag();



<?php
error_reporting(0);
include 'config.php';
# Let's play games ~

$vuln = (isset($_GET['vuln'])) ? $_GET['vuln'] : 'x';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('err');

if(preg_match("/_|database/i", $vuln)) die('die(0)');

$usercode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE usercode={$vuln}"));
$username = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE username='{$vuln}'"));
$password = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE password=\"{$vuln}\""));
$x = mysqli_fetch_row(mysqli_query($conn, "SELECT database()"));

if($vuln !== 'x'){
	if($usercode['usercode'] === '007'){
		if($username['username'] === 'bang'){
			if($password['password'] === $usercode['usercode']){
				if($x[0] === $username['password']){
					die($flag);
				} else echo 'die(4)';
			} else echo 'die(3)';
		} else echo 'die(2)';
	} else echo 'die(1)';
} echo '<hr>';

if(isset($_GET['view-source'])){
	highlight_file(__FILE__) and die();
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>sqlgame</title>
	</head>
	<body>
		<!-- index.php?view-source -->
		<center>
			<img src="./assets/logo.png"><br>
			<form>
			<input type="text" name="vuln" placeholder="vuln">
			<input type="submit" value="query">
			</form>
			<a href='?view-source'>source</a>
		</center>
	</body>
</html>
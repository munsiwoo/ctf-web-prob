<?php
error_reporting(0);
include 'config.php';
# made by munsiwoo
# flag is in the database

if(isset($_GET['view-source'])){
	highlight_file(__FILE__);
	exit();
}

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('connect error');

if(isset($_GET['login'])) {
	$login_id = addslashes($_POST['login_id']);
	$login_pw = addslashes($_POST['login_pw']);
	$query = "SELECT * FROM users WHERE login_id='{$login_id}' AND login_pw='{$login_pw}'";

	if($row = mysqli_fetch_array(mysqli_query($con, $query))) {
		$success = 'your name : '.htmlspecialchars($row['login_name']);
		exit($success);
	}
	exit('wrong');
}

if(isset($_GET['join'])) {
	$join_info = explode(':', $_GET['join']);
	$join_id = $join_info[0];
	$join_pw = $join_info[1];
	$join_name = $join_info[2];

	if(strlen($join_id) < 5 || strlen($join_pw) < 5 || strlen($join_name) < 5){
		exit('id or pw or name is too short');
	}

	if(preg_match('/\'|\"|\`|schema|mysql|union/i', $join_id.' '.$join_pw.' '.$join_name)){
		exit('403 forbidden');
	}

	$query = "SELECT * FROM users WHERE login_id='{$join_id}'";

	if(mysqli_fetch_array(mysqli_query($con, $query))){
		exit('id is already exists');
	}

	reset_chk(); // table reset

	$query = "INSERT INTO users VALUES ('{$join_id}', '{$join_pw}', '{$join_name}')";
	mysqli_query($con, $query) or die('join query error');

	sleep(1);
	exit('join ok');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>?</title>
</head>
<body>
	<center>
	<h1>Login</h1>
	<form action="?login" method="POST">
		<input type="text" name="login_id" placeholder="login id" />
		<input type="password" name="login_pw" placeholder="login pw" />
		<input type="submit" value="login" />
	</form>
	<br>
	<h1>Join</h1>
	<form method="GET">
		<input type="text" name="join" placeholder="join information" />
		<input type="submit" value="join" />
	</form>
	<br />
	<!--
	<a href='?view-source'><button><h3>view source</h3></button></a>
	-->
	</center>
</body>
</html>
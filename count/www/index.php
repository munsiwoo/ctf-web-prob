<?php
error_reporting(0);

function random_generator($len=40) {
	$str = implode('', array_merge(range('a','z'), range(0, 9)));
	return substr(sha1(str_shuffle($str)), 0, $len);
}

if(strlen($_COOKIE['session']) < 10) {
	$_COOKIE['session'] = random_generator();
	setcookie("session", $_COOKIE['session'], time()+3600);
}

if(isset($_GET['create'])) {
	$sandbox_dir = 'sandbox/'.md5($_COOKIE['session']).'/';
	
	if(is_dir($sandbox_dir)) {
		echo '<center><h3>';
		die('your sandbox is <a href="'.$sandbox_dir.'">here</a>');
	}
	
	mkdir($sandbox_dir, 0777);

	$source = file_get_contents('template.tpl');
	$source = str_replace('[rand]', random_generator(8), $source);
	$flag = file_get_contents('flag-6ece7416.php');

	file_put_contents($sandbox_dir.'index.php', $source);
	file_put_contents($sandbox_dir.'flag.php', $flag);

	echo '<center><h3>';
	die('your sandbox is <a href="'.$sandbox_dir.'">here</a>');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<center>
		<h3>Do you want to start challenge?</h3>
		<a href="?create"><button>create my sandbox</button></a>
	</center>
</body>
</html>
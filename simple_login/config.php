<?php
define("DB_HOST" , "**secret**");
define("DB_USER", "**secret**");
define("DB_PASSWORD", "**secret**");
define("DB_NAME", "**secret**");

function reset_chk(){
	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('connect error');

	$query = "SELECT count(*) FROM users WHERE 1";
	$cnt = mysqli_fetch_array(mysqli_query($con, $query));

	if(50 <= (int)$cnt[0]){
		$reset = "TRUNCATE TABLE users";
		mysqli_query($con, $reset) or die('reset error1');

		$bulid = "INSERT INTO users (login_id, login_pw, login_name) VALUES".
				 "('**secret**', '**secret**', '**secret**'),".
				 "('**secret**', '**secret**', '**secret**'),".
				 "('**secret**', '**secret**', '**secret**'),".
				 "('**secret**', '**secret**', '**secret**'),".
				 "('**secret**', '**secret**', '**secret**')";

		mysqli_query($con, $bulid) or die('reset error2');
	}
	return 0;
}
?>
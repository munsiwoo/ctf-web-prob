<?php
error_reporting(0);

function go($locate, $message = "") { // redirect
	$execute  = "<script>location.href=\"?p={$locate}\";";
	$execute .= strlen($message) ? "alert(\"{$message}\");" : "";
	$execute .= "</script>"; die($execute);
}

function back($message = "") { // history back
	$execute  = "<script>history.back();";
	$execute .= strlen($message) ? "alert(\"{$message}\");" : "";
	$execute .= "</script>"; die($execute);
}

function secure_page($page) { // anti hack
	$page = strtolower(trim($page));
	$page = str_replace(chr(0), '', $page);
	$page = str_replace('../', '', $page);
	if(substr($page, -4, 4) == 'html') {
		return $page;
	} 
	die('403 forbidden.');
}

function secure_waf() { // anti hack
	$regex  = "/information_schema|union.*select|";
	$regex .= "<script>|src.*=[\"|'].*[\"|']|on.{4,}/is";

	foreach($_REQUEST as $value) {
		if(preg_match($regex, $value)) return true;
	}

	return false;
}

function session_verify() { // check session
	return isset($_SESSION['user']);
}

function error($message) { // error message
	echo "<br><center><h3>{$message}</h3></center>";
	die(file_get_contents('footer.php'));
}

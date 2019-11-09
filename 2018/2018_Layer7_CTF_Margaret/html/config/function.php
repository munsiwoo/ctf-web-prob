<?php
error_reporting(0);

function go($url, $msg = "") { // redirect
	$execute  = "<script>location.href=\"{$url}\";";
	$execute .= strlen($msg) ? "alert(\"{$msg}\");" : "";
	$execute .= "</script>"; die($execute);
}

function back($msg = "") { // history back
	$execute  = "<script>history.back();";
	$execute .= strlen($msg) ? "alert(\"{$msg}\");" : "";
	$execute .= "</script>"; die($execute);
}

function secure_page($page) { // anti lfi
	$page = strtolower(trim($page));
	$page = str_replace(chr(0), '', $page); 
	$page = str_replace('../', '', $page);
	if(substr($page, -4, 4) == 'html') return $page;
}

function password($password) {
	return hash('sha256', sha1(md5($password).__SALT__));
}

function anti_sqli($data) { // anti sqlite injection
	return str_replace("'", "''", $data);
}

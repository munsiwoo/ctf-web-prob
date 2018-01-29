<?php
	error_reporting(0);
	require("flag.php");
	# made by munsiwoo

	$x = 1337;
	$e = array();
	$cookies = $_COOKIE;
	extract($cookies);
	
	$elements="apple|banana|strawberry".
	"melon|lemon|mango|peach|tangerine".
	"grapes|coconut|orange|kiwi|ʕ•ᴥ•ʔ";

	$fruits = explode("|", $elements);
	shuffle($fruits);

	foreach($fruits as $fruit){
		$e[$x++] = $fruit;
	}
	
	if(preg_match("/([0-9]|[a-z])/i", $a)) die("ʕ•ᴥ•ʔ");
	if(preg_match("/([0-9]|[a-z])/i", $b)) die("ʕ•ᴥ•ʔ");
	$p = strcmp($e[strlen($elements)^strlen($x)][9],chr(103));
	
	if(strcmp($p, (int)"ʕ•ᴥ•ʔ") == "ʕ•ᴥ•ʔ"){
		if((int)($a^$b) === strlen($x)){
			show_flag();
		}
	}
	
	show_source(__FILE__);
?>
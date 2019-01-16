<?php
error_reporting(0);
require_once 'flag.php';
# made by munsiwoo

if(isset($_GET['source'])) {
	show_source(__FILE__);
	exit;
}

$cnt_file = 'cnt-[rand]';

if(!file_exists($cnt_file)) {
	file_put_contents($cnt_file, '10');
}

$cnt = (int)file_get_contents($cnt_file); // read count

if($cnt == 30) { // integer overflow? no..
    $cnt = 10;
}

$cnt = $cnt + 1;
file_put_contents($cnt_file, $cnt); // write count

if($cnt == 1) {
    die($flag);
}

echo $cnt;
?>
<br><a href="?source">source</a>

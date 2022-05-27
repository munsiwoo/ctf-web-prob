<?php
if($_SERVER['REMOTE_ADDR']!=='210.217.38.14') die('permission denied');
$db = new mysqli('127.0.0.1', 'admin', 'password', 'rpo');

$result = $db->query("SELECT * FROM urls WHERE is_read=0");
$fetch = $result->fetch_array(MYSQLI_ASSOC);

if($fetch){
    $db->query("UPDATE urls SET is_read=1 WHERE no={$fetch['no']}");
    echo $fetch['url'];
} else {
    echo '0';
}

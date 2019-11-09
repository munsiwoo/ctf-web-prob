<?php
error_reporting(0);
include 'config.php';
# flag is in the database

if(preg_match('/info|like|id|_/i', $_GET['id'])) exit("403 forbidden");
if(strlen($_GET['id']) > 10) exit("id is too long");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("connect error");
$query = "SELECT id FROM `can_you_do_it` WHERE id='".$_GET['id']."'";
$row = mysqli_fetch_array(mysqli_query($conn, $query);

echo "<hr>";
if($row['id']){
	echo $row['id'];
}
else {
	echo "None";
}
echo "<hr>";

highlight_file(__FILE__);
?>
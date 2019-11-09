<?php
error_reporting(0);
session_start();
include '../include/library.php';

function contacts_delete($idx){
	global $conn;

	$delete_query = "DELETE FROM contacts WHERE idx='".(int)$idx."'";
	mysqli_query($conn, $delete_query) or die('error');
}

function contacts_read(){
	global $conn;

	$read_query = "SELECT idx, contents FROM contacts ORDER BY idx ASC LIMIT 0, 1";

	$row = mysqli_fetch_assoc(mysqli_query($conn, $read_query)) or die('none');
	contacts_delete($row['idx']);

	echo $row['contents'];
}
?>
<?php
	error_reporting(0);
	include 'config.php';

	if(isset($_GET['go'])){
		$filter = "/\'|\"|`|,|<|>|&|=|;|#|_|or|and|union|select|into|info|sc|in|like|regex|rand|limit|prob|0x|0b/i";
		if(preg_match($filter, $_GET['go'])) exit("403 forbidden");
		if(preg_match("/\s/", $_GET['go'])) exit("whitespace nono");

		$i = 0;
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = mysqli_query($conn, "SELECT * FROM `is_this_possible` ORDER BY ".$_GET['go']." DESC");

		echo "<table style='border: 1px solid black;'>";
		echo "<tr><th>id</th></tr>";
		while($row = mysqli_fetch_array($query)){
			$res[$i++] = $row['id'];
			echo "<tr><td>{$row['id']}</td></tr>";
		}
		echo "</table><hr>";

		if($res[0] === "admin" && $res[1] === "19990301" && $res[2] === "guest"){
			if((int)$res[1] == $_GET['foo'] && strlen($_GET['foo']) > 10){
				solve();
			}
		}
	}
	highlight_file(__FILE__);
?>
<?php
error_reporting(0);

if(isset($_GET['source'])) {
	show_source(__FILE__);
	die;
}

function get_suffix() {
	return '_'.md5(random_bytes(20));
}

if(isset($_FILES['file'])) {
	$filename = $_FILES['file']['name'].get_suffix();
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
		die('<script>alert("upload ok");location.href="'.$filename.'";</script>');
	}

	else {
		die('<script>alert("upload failed!");location.href="upload.php";</script>');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<form method="POST" enctype="multipart/form-data">
		<input type="file" name="file">
		<input type="submit" value="upload">
	</form>
	<br>
	<a href="?source">source</a>
</body>
</html>



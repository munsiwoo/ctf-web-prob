<?php
error_reporting(0);
# made by munsiwoo

if(isset($_GET['source'])) {
	show_source(__FILE__);
	exit;
}

function random_str() {
	$str = array_merge(range(0,9), range('a', 'z'));
	shuffle($str);
	$result = substr(implode('', $str), mt_rand(0,5), mt_rand(20, 30));
	return sha1($result);
}

function move_to_backup($original_path, $ext) {
	$filename = random_str().'.'.$ext;
	$backup_path = 'backup/'.$filename;

	rename($original_path, $backup_path);
	if(file_exists($backup_path)) {
		return true;
	}

	return false;
}

if(isset($_FILES['file'])) {
	$filename = $_FILES['file']['name'];
	$ext = pathinfo($filename)['extension'];
	$disallow_ext = ['php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phtm', 'htaccess'];

	if(in_array($ext, $disallow_ext)) {
		die('💩');
	}
	
	move_uploaded_file($_FILES['file']['tmp_name'], $filename);
	$binary = base64_encode(file_get_contents($filename));
	$size = getimagesize($filename);
	
	if(move_to_backup($filename, $ext)) {
		echo "<h3>Preview your uploaded file ({$size[0]}x{$size[1]})</h3>";
		echo '<img src="data:image/png;base64,'.$binary.'">';
	}
	else {
		echo '<h3>backup failed.</h3>';
	}
}
	

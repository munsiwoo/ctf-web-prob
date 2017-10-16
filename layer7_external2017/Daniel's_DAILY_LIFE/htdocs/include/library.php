<?php
error_reporting(0);
session_start();
include 'config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('server down');

function xss_block($string){
	$filter = "/<|>/";
	$result = preg_replace($filter, '', $string);
	return $result;
}

function sqli_block($string){
	$filter = "/_|\'|\`|\\\\|and|or/i";
	$result = preg_replace($filter, '', $string);
	return $result;
}

function view_category($contents){
	return preg_replace('/<[^>]*>/s', '', $contents);
}

function bbcode($string){
	$tags = 'b|i|size|color|center|code|quote|url|img';
	while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)){
		foreach ($matches[0] as $key => $match){
			list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
			switch($tag){
				case 'b': $replacement = "<strong>{$innertext}</strong>"; break;
				case 'i': $replacement = "<em>{$innertext}</em>"; break;
				case 'size': $replacement = "<span style=\"font-size: $param;\">{$innertext}</span>"; break;
				case 'color': $replacement = "<span style=\"color: $param;\">{$innertext}</span>"; break;
				case 'center': $replacement = "<div class=\"centered\">{$innertext}</div>"; break;
				case 'code': $replacement = "<pre>{$innertext}</pre>"; break;
				case 'quote': $replacement = "<blockquote>{$innertext}</blockquote>".$param ? "<cite>{$pram}</cite>" : ""; break;
				case 'url': $replacement = "<a href=\"".($param ? $param : $innertext)."\">{$innertext}</a>"; break;
				case 'img':
					list($width, $height) = preg_split('`[Xx]`', $param); 
                    $replacement = "<img src=\"{$innertext}\" ".
                    (is_numeric($width) ? "width=\"{$width}\" " : '').
                    (is_numeric($height) ? "height=\"{$height}\" " : "")."/>"; 
                break;
            }
            $string = str_replace($match, $replacement, $string);
        }
    }
    return $string;
}

function token_chk($username, $token){
	global $conn;

	$username = sqli_block($username);
	$token = sha1(md5($_SERVER['REMOTE_ADDR']));
	$chk_query = "SELECT * FROM `users` WHERE username='{$username}'";

	if($assoc = mysqli_fetch_assoc(mysqli_query($conn, $chk_query))){
		if($token === $assoc['token']){
			return 1;
		}
	}
	return 0;
}

function send($title, $contents){
	global $conn;

	if(strlen($title) < 1 || strlen($contents) < 1){
		exit("<script>alert('title or contents is too short');location.href='contacts.php';</script>");
	}
	
	$title = sqli_block(xss_block(substr($title, 0, 50)));
	$contents = sqli_block(bbcode(xss_block($contents)));
	$contents = substr(bbcode($contents), 0, 512);
	$contacts_query = "INSERT INTO `contacts` VALUES (NULL, '".$title."', '".$contents."')";

	echo $contacts_query;

	mysqli_query($conn, $contacts_query) or die('send error');
	exit("<script>alert('send ok');location.href='./contacts.php'</script>");
}

function login($username, $password){
	global $conn;

	$username = sqli_block(substr($username, 0, 50));
	$password = hash('sha256', $password._SALT_);
	$login_query = "SELECT * FROM `users` WHERE username='{$username}' AND password='{$password}'";

	if($assoc = mysqli_fetch_assoc(mysqli_query($conn, $login_query))){
	    $_SESSION['username'] = $assoc['username'];
	    $_SESSION['token'] = sha1(md5($_SERVER['REMOTE_ADDR']));

		exit("<script>location.href='./home.php';</script>");
	}

	exit("<script>alert('wrong');location.href='./login.php';</script>");
}

function add_member($userip, $username, $password){
	global $conn;

	if(strlen($username) < 5 || strlen($password) < 5){
		exit('username or password is too short');
	}

	$token = sha1(md5($userip));
	$username = sqli_block(substr($username, 0, 50));
	$password = hash('sha256', $password._SALT_);
	$chk_query = "SELECT * FROM `users` WHERE username='{$username}'";

	if(mysqli_fetch_assoc(mysqli_query($conn, $chk_query))){
		exit("<script>alert('username already exists');location.href='./add_member.php';</script>");
	}

	$join_query = "INSERT INTO `users` VALUES ('{$token}', '{$username}', '{$password}')";
	mysqli_query($conn, $join_query) or die('join fail');

	exit("<script>alert('join ok');location.href='../login.php';</script>");
}

?>
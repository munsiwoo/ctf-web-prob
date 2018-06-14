<?php
error_reporting(0);
require_once 'database.class.php'; // Database class
define("MINIMUM_LEN", 5); // name minimum length

class User extends Database {

	public function user_login($user) {
		$user = array_map('addslashes', $user); // anti sql injection
		$username = $user['username'];
		$password = sha1(md5($user['password'])); // password hash
		
		$query  = "select * from users where ";
		$query .= "username='{$username}' and password='{$password}';";
		$query  = $this->query($query);

		if($fetch = $this->fetch($query)) {
			return $fetch;
		}

		return false;
	}

	public function user_join($user) {
		$user = array_map('addslashes', $user); // anti sql injection
		$nickname = substr($user['nickname'], 0, 100); // anti sql truncate attack
		$username = substr($user['username'], 0, 100); // anti sql truncate attack
		$password = sha1(md5($user['password']));

		if($message = $this->check_names($nickname, $username)) { // call name check function
			return $message;
		}

		$query = "insert into users values ('{$nickname}', '{$username}', '{$password}');";
		$this->query($query) or die($query);

		return false;
	}

	private function check_names($nickname, $username) {
		$nickname_length = mb_strlen($nickname, 'UTF-8');
		$username_length = mb_strlen($username, 'UTF-8');

		$query  = "select * from users where ";
		$query .= "nickname='{$nickname}' or username='{$username}';";
		$query  = $this->query($query);

		if($nickname_length < MINIMUM_LEN || $username_length < MINIMUM_LEN) { // length check
			return 'nickname or username is too short.';
		}
		if(preg_match('/<|>/', $nickname.$username)) { // xss check
			return 'nickname or username contains an html tag.';
		}
		if($this->fetch($query)) { // duplicate check
			return 'already exists nickname or username.';
		}

		return false; // pass
	}
}

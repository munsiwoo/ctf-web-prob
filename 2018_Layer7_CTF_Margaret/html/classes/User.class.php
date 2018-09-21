<?php
class User extends SQLite3 {
	function __construct() {
		parent::__construct(__DB__);
	}

	public function login($data) {
		$data = array_map('anti_sqli', $data);
		
		$username = $data['username'];
		$password = password($data['password']);

		$query = "SELECT * FROM `users` WHERE `username`='{$username}' AND `password`='{$password}';";
		$query = $this->query($query);

		if($fetch = $query->fetchArray()) {
			$_SESSION['username'] = $fetch['username'];
			go('/home.html', 'login success');
		}

		die('<h2>login failed.</h2>'); // login fail
	}

	public function register($data) {
		$data = array_map('anti_sqli', $data);

		$username = $data['username'];
		$password = password($data['password']);

		if(preg_match("/(\s|admin|_)/i", $username, $matche)) {
			die('keyword "'.$matche[0].'" is not allowed');
		}

		if(strlen($password) < 5) {
			die('password is too short');
		}

		$query = "SELECT * FROM `users` WHERE `username`='{$username}';";
		$query = $this->query($query);
		if($query->fetchArray()) die('already exists');

		$query = "INSERT INTO `users` VALUES ('{$username}', '{$password}');";
		$query = $this->query($query);

		go('/login.html', 'register success');
	}

}

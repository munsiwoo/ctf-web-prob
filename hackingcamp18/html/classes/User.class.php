<?php

class User extends SQLite3 {
	function __construct() {
		parent::__construct(__DB__);
	}

	private function anti_sqli($data) {
		return str_replace("'", "''", $data);
	}

	public function login($data) {
		$data = array_map('anti_sqli', $data);
		
		$query = "SELECT * FROM `users` WHERE `username`='{$data['username']}' AND `password`='{$data['password']}';";
		$query = $this->query($query);
		$fetch = $query->fetchArray();

		if($fetch['username']) {
			$_SESSION['username'] = $fetch['username'];
			go('/home', 'login success');
		}

		die('<h2>login failed.</h2>'); // login failed
	}

	public function register($data) {
		$data = array_map('anti_sqli', $data);

		if(preg_match("/(\s|admin|_)/i", $data['username'], $matche)) {
			die('keyword "'.$matche[0].'" is not allowed');
		}

		if(strlen($data['password']) < 5) {
			die('password is too short');
		}

		$query = "SELECT * FROM `users` WHERE `username`='{$data['username']}';";
		$query = $this->query($query);
		if($query->fetchArray()) die('already exists');

		$query = "INSERT INTO `users` VALUES ('{$data['username']}', '{$data['password']}', 100);";
		$query = $this->query($query);

		go('/login', 'register success');
	}

}

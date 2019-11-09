<?php

class Shop extends SQLite3 {
	function __construct() {
		parent::__construct(__DB__);
	}

	private function get_user_information($username) {
		$user_query = "SELECT * FROM `users` WHERE `username`='{$username}';";
		$user_query = $this->query($user_query);
		$user_fetch = $user_query->fetchArray();

		return $user_fetch;
	}

	private function get_item_information($item) {
		$item_query = "SELECT * FROM `shop` WHERE `item`='{$item}';";
		$item_query = $this->query($item_query);
		$item_fetch = $item_query->fetchArray();

		if(!$item_fetch) {
			go('/shop', 'selected item does not exist.');
		}

		return $item_fetch;
	}

	private function update_balance($username, $balance) {
		$update_query = "UPDATE `users` SET `money`='{$balance}' WHERE `username`='{$username}';";
		$this->query($update_query);

		return true;
	}

	public function buy($data) {
		if(!isset($_SESSION['username'])) {
			go('/login', 'please login first.');
		}
		$data = array_map('anti_sqli', $data);
		
		$item = $this->get_item_information($data['item']);
		$user = $this->get_user_information($_SESSION['username']);

		$item_price = (int)$item['price'];
		$user_money = (int)$user['money'];
		if($user_money < $item_price && $user['username'] != 'admin') {
			go('/shop', 'not enough coin!');
		}

		$username = anti_sqli($user['username']);
		$balance = $user_money - $item_price;
		$this->update_balance($username, $balance);

		echo '<script>alert("purchase success");</script>';
		go('/shop', $item['content']);
	}


}

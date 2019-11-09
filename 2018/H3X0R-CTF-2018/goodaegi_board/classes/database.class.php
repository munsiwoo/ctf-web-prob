<?php
error_reporting(0);

class Database {
	private $conn;
	
	function __construct($conn) {
		$this->conn = mysqli_connect(
			$conn['host'], $conn['user'],
			$conn['pass'], $conn['name']
		) or die('sql server down');
	}

	protected function fetch($query) {
		return mysqli_fetch_assoc($query);
	}

	protected function query($query) {
		return mysqli_query($this->conn, $query);
	}
}


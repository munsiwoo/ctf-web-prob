<?php
error_reporting(0);
session_start();

require_once 'config/dbconfig.php'; // database config
require_once 'config/function.php'; // various functions
require_once 'classes/user.class.php'; // User class
require_once 'classes/board.class.php'; // Board class

class Control {
	function __construct($page, $method) {
		$User  = new User($GLOBALS['conn']);
		$Board = new Board($GLOBALS['conn']);

		if($method == 'POST' && secure_waf()) { // check POST method requests
			error('403 forbidden.');
		}

		if($page == 'logout.html' && $method == 'GET') { // logout
			session_destroy();
			go('home.html', 'logout ok.');
		}

		else if($page == 'login.html' && $method == 'POST') { // login
			if(isset($_POST['submit'])) {
				if($fetch = $User->user_login($_POST)) {
					$_SESSION['user'] = $fetch;
					go('home.html', 'login ok.');
				}
				go('login.html', 'login failed.');
			}
			error('invalid request.');
		}

		else if($page == 'join.html' && $method == 'POST') { // join
			if(isset($_POST['submit'])) {
				if($message = $User->user_join($_POST)) {
					back($message);
				}
				go('login.html', 'join ok.');
			}
			error('invalid request.');
		}

		else if($page == 'board.html' && $method == 'GET') { // board
			if(session_verify() == false) {
				go('login.html', 'first login.');
			}
			$this->load_page('board_header.html');
			echo $Board->load_data($_SESSION['user']['username']);
			$this->load_page('board_footer.html');
		}

		else if($page == 'insert.html' && $method == 'POST') { // write
			if(session_verify() == false) {
				go('login.html', 'first login.');
			}
			if(isset($_POST['submit'])) {
				$username = addslashes($_SESSION['user']['username']);
				$Board->insert_data($_POST, $username);
				go('board.html', 'insert ok.');
			}
			error('invalid request.');
		}

		else { // general
			$this->load_page($page);
		}

	}

	private function load_page($page) {
		$template = 'templates/'.$page;
		if(!file_exists($template)) { // page not exists
			error('404 not found');
		}
		include_once $template;
	}
}
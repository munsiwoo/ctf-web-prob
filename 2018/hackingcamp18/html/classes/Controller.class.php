<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Render.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/classes/User.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Shop.class.php';

class Controller {
	function __construct($method, $page, $session) {
		$Render = new Render();
		$User = new User();
		$Shop = new Shop();

		if(!$session) {
			$not_allow_page = ['mypage', 'logout'];
			if(in_array($page, $not_allow_page)) {
				header("HTTP/1.0 403 Forbidden");
				die('403 Forbidden');
			}
		}

		if($page == 'logout' && $session) {
			session_destroy();
			go('/home', 'logout success');
		}
		else if($page == 'login' && $method == 'POST') {
			$User->login($_POST);
		}
		else if($page == 'register' && $method == 'POST') {
			$User->register($_POST);
		}
		else if($page == 'buy' && $method == 'POST') {
			$Shop->buy($_POST);
		}
		else { // GET
			$Render->render_template('header');
			$Render->render_template($page);
			$Render->render_template('footer');
		}
	}
}

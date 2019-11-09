<?php

class Render extends SQLite3 {
	function __construct() {
		parent::__construct(__DB__);
	}
	public function render_template($page, $menu=[]) {
		$dir = $_SERVER['DOCUMENT_ROOT'].'/templates/'.$page.'.html';
		$html = file_get_contents($dir);

		if($page == 'header') {
			$html = str_replace('{MENU}', $this->menu_loader(), $html);
		}
		if($page == 'mypage') {
			$username = anti_sqli($_SESSION['username']);
			$query = $this->query("SELECT * FROM `users` WHERE `username`='{$username}';");
			$fetch = array_map('htmlspecialchars', $query->fetchArray());

			$html = str_replace('{USERNAME}', $fetch['username'], $html);
			$html = str_replace('{MONEY}', $fetch['money'], $html);
		}

		echo preg_replace('/\n+|\t+/', '', $html); // render
	}

	public function menu_loader() {
		$menu_list = isset($_SESSION['username']) ? 
		['home', 'shop', 'mypage', 'logout'] : ['home', 'shop', 'login', 'register'];

		$result = "<table><tr>";
		foreach($menu_list as $menu) {
			$result .= "<td style=\"padding-right: 15px;\">".
			"<a href=\"/{$menu}\" style=\"font-size:30px;\">{$menu}</a></td>";
		}
		return $result."</tr></table>";
	}
}

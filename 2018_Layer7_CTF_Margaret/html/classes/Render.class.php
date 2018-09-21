<?php
class Render extends SQLite3 {
	function __construct() {
		parent::__construct(__DB__);
	}

	public function render_template($page) {
		$file = $_SERVER['DOCUMENT_ROOT'].'/templates/'.$page;

		switch($page) {
			case 'header.html' :
				$html = file_get_contents($file);
				$html = str_replace('{MENU}', $this->menu_loader(), $html);
				echo preg_replace('/\n+|\t+|\s{2}/', '', $html); // render
				break;
			case 'mypage.html' :
				$html = file_get_contents($file);
				$fetch = array_map('htmlspecialchars', $this->mypage_loader($_SESSION['username'])); 
				$html = str_replace('{USERNAME}', $fetch['username'], $html);
				$html = str_replace('{PASSWORD}', $fetch['password'], $html);
				echo preg_replace('/\n+|\t+|\s{2}/', '', $html); // render
				break;
			default :
				include $file;
				break;
		}
	}

	private function menu_loader() {
		$menu_list = isset($_SESSION['username']) ? 
		['home'=>'/home.html', 'mypage'=>'/mypage.html', 'logout'=>'/logout.html'] :
		['home'=>'/home.html', 'login'=>'/login.html', 'register'=>'/register.html'];

		$result  = '<table><tr>';
		foreach($menu_list as $menu=>$url) {
			$result .= '<td style="padding-right: 15px;">'.
			"<a href=\"{$url}\" style=\"font-size:30px;\">{$menu}</a></td>";
		}
		$result .= '</tr></table>';

		return $result;
	}

	private function mypage_loader($username) {
		$username = anti_sqli($username);
		$query = $this->query("SELECT * FROM `users` WHERE `username`='{$username}';");
		return $query->fetchArray();
	}

}

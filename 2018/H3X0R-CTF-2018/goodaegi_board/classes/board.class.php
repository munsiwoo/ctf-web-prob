<?php
error_reporting(0);
require_once 'database.class.php'; // Database class

class Board extends Database {
	
	public function load_data($username) { // load posts
		$username = addslashes($username);

		$board_frame = file_get_contents('templates/board_body.html');
		$query = "select * from board where username='{$username}' order by cast(no as signed) desc;";
		$query = $this->query($query);
		$posts = '';

		while($fetch = $this->fetch($query)) {
			$fetch = array_map('htmlspecialchars', $fetch);
			$post = preg_replace("/\[title\]/", $fetch['title'], $board_frame);
			$post = preg_replace("/\[content\]/", $fetch['content'], $post);
			$posts .= $post;
		}

		return $posts;
	}

	public function insert_data($data, $username) { // write
		$data = array_map('addslashes', $data);
		$title = $data['title'];
		$content = $data['content'];

		$query = "select count(*) as `no` from board;";
		$fetch = $this->fetch($this->query($query));

		$insert_no = (int)$fetch['no'] + 1;
		$query  = "insert into board values ";
		$query .= "($insert_no, '{$title}', '{$content}', '{$username}');";

		$this->query($query) or die('insert failed.');

		return true;
	}

}

<?php
class Render extends Aleph {
    function __construct() {
        parent::__construct();
    }

    public function top_menu($is_login) {
        $retval = $is_login ? __USER_MENU__ : __GUEST_MENU__;
        return $retval;
    }

    public function all_videos() {
        $retval = $this->query('SELECT', ['table' => 'videos']);
        return $retval;
    }

    public function get_user_info() {
        $user = $this->query('SELECT', ['table' => 'users', 'username' => $_SESSION['username']])[0];
        return $user;
    }

}

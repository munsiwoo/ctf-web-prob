<?php
class User extends Aleph {
    function __construct() {
        parent::__construct();
    }

    public function login($data) {
        $username = $data['username'];
        $password = process_password($data['password']);
        $query = [
            'table' => 'users',
            'username' => $username,
            'password' => $password,
        ];
        $user = $this->query('SELECT', $query)[0];
        $retval = ['status' => false];

        if($user) {
            $_SESSION['username'] = $user['username'];
            $retval['msg'] = "Hello, {$user['username']}";
            $retval['status'] = true;
        }
        else {
            $retval['msg'] = "Login failed.";
        }

        return json_encode($retval);
    }

    public function register($data) {
        $username = substr($data['username'], 0, 50);
        $password = process_password($data['password']);
        $retval = ['status' => false];

        if(preg_match('/[^\w\sㄱ-힣_\-+]+/mis', $username)) {
            $retval['msg']  = 'Allowed range :'.PHP_EOL; 
            $retval['msg'] .= 'alphabet, number, hangul, whitespace, _, -, +';
            return json_encode($retval);
        }
        else if(mb_strlen($data['password']) < 5) {
            $retval['msg'] = 'Your password is too short. (more than 5digits)';
            return json_encode($retval);
        }

        $check_query = [
            'table' => 'users',
            'username' => $username,
            'password' => $password,
        ];

        if($this->query('SELECT', $check_query)) {
            $retval['msg'] = 'Already exists username!';
            return json_encode($retval);
        }

        $query = [
            'table' => 'users',
            'username' => $username,
            'password' => $password,
            'joined' => date('Y-m-d'),
            'attendance' => 0,
            'premium' => 0,
        ];

        $this->query('INSERT', $query);
        $retval['msg'] = 'Registration completed.';
        $retval['status'] = true;
    
        return json_encode($retval);
    }

    public function check_attendance() {
        $retval = ['status' => false];

        if($_SESSION['ad_datetime'] >= (time() - __AD_DELAY_SEC__)) {
            $minute = __AD_DELAY_SEC__ / 60;
            $retval['msg'] = "Attendance is only available once every {$minute} minutes.";
            return json_encode($retval);
        }

        $user = $this->query('SELECT', ['table' => 'users', 'username' => $_SESSION['username']])[0];
        $user['attendance'] += 1;

        $this->query('DELETE', ['table' => 'users', 'username' => $_SESSION['username']]);
        $this->query('INSERT', $user);
        $_SESSION['ad_datetime'] = time();

        $retval['status'] = true;
        $retval['msg'] = "Attendance is complete!";
        return json_encode($retval);
    }

    public function get_premium() {
        $retval = ['status' => false];
        $user = $this->query('SELECT', ['table' => 'users', 'username' => $_SESSION['username']])[0];
        if($user['attendance'] >= 100 && $user['premium'] == 0) {
            $user['premium'] = 1;

            $this->query('DELETE', ['table' => 'users', 'username' => $_SESSION['username']]);
            $this->query('INSERT', $user);

            $retval['status'] = true;
            $retval['msg'] = "Congrats, You have been promoted to a premium!";
            return json_encode($retval);
        }
        else if($user['premium'])
            $retval['msg'] = "You are already premium.";
        else if($user['attendance'] < 100)
            $retval['msg'] = "Attendance must exceed 100 to qualify for premium status.";
        else
            $retval['msg'] = "Unknown error.";

        return json_encode($retval);
    }

    public function is_premium() {
        $user = $this->query('SELECT', ['table' => 'users', 'username' => $_SESSION['username']])[0];
        $is_premium = $user['premium'];
        return $is_premium;
    }

    public function is_user() {
        $is_user = $this->query('SELECT', ['table' => 'users', 'username' => $_SESSION['username']]) ? true : false;
        return $is_user;
    }

}

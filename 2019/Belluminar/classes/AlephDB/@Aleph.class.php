<?php
class Aleph {
    private $host, $port;

    function __construct() {
        $this->host = __DB_HOST__;
        $this->port = __DB_PORT__;
    }

    private function encrypt_data($data) {
        $split_data = str_split($data);
        $retval = '';

        foreach($split_data as $str)
            $retval .= $str ^ "\x01";

        return $retval;
    }

    private function make_query($command, $data) { 
        if($command == 'INSERT')
            $header = "\x01";
        else if ($command == 'SELECT')
            $header = "\x02"; 
        else if($command == 'DELETE')
            $header = "\x03"; 

        $condition = json_decode($data, true); // json to array
        $send_data = $header;

        foreach ($condition as $key=>$val) {
            $val = $this->encrypt_data($val);
            $send_data .= "{$key}\xff{$val}\xff\x02";
        }

        $send_data = substr($send_data, 0, -1)."\x01"; // End
        return $send_data;
    }

    private function select($data, $conn) {
        $query = $this->make_query('SELECT', $data);
        socket_send($conn, $query, strlen($query), MSG_EOF);
        $select_data = "";

        while(($read_data = socket_read($conn, 4096)) !== "") {
            $select_data .= $read_data;
        }

        $retval = json_decode($select_data, true);
        return $retval;
    }

    private function insert($data, $conn) {
        $query = $this->make_query('INSERT',$data);
        socket_send($conn, $query, strlen($query), MSG_EOF);

        return true;
    }

    private function delete($data, $conn) {
        $query = $this->make_query('DELETE', $data);
        socket_send($conn, $query, strlen($query), MSG_EOF);

        return true;
    }

    public function query($command, $data) {
        $conn = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($conn, $this->host, $this->port);

        if(is_array($data)) $data = json_encode($data);
        switch(strtoupper($command)) {
            case 'SELECT' :
                $retval = $this->select($data, $conn);
                break;
            case 'INSERT' :
                $retval = $this->insert($data, $conn);
                break;
            case 'DELETE' :
                $retval = $this->delete($data, $conn);
                break;
            default :
                $retval = false;
        }

        socket_close($conn);
        return $retval;
    }
}

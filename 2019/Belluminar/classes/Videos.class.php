<?php
class Videos extends Aleph {
    private $conn;
    
    function __construct() {
        $this->conn = parent::__construct();
    }

    public function upload($data) {
        $retval = ['status' => false];

        $title = substr($data['title'], 0, 35);
        $contents = substr($data['contents'], 0, 50);
        $video_url = $data['video_url'];
        $username = $_SESSION['username'];
        $video_info = $this->get_video_info($video_url);

        if(isset($video_info['error'])) {
            $retval['msg'] = $video_info['error'];
            return json_encode($retval);
        }

        $no = $this->get_last_no() + 1;
        $query = [
            'table' => 'videos',
            'no' => $no,
            'title' => addslashes($title),
            'contents' => addslashes($contents),
            'video' => json_encode($video_info['result']),
            'username' => $username,
        ];

        $this->query('INSERT', $query);
        $retval['status'] = true;
        $retval['msg'] = 'Upload success.';
        $retval['rurl'] = "/read?no={$no}";

        return json_encode($retval);
    }

    public function read($data) {
        $no = (int)$data['no'];
        $retval = ['status' => false];
        $result = $this->query('SELECT', ['table' => 'videos', 'no' => $no])[0];

        if(!$result) {
            $retval['msg'] = 'Video does not exist.';
            return $retval;
        }

        $retval['status'] = true;
        $retval['result'] = $result;

        return $retval;
    }

    public function delete($data) {
        $no = (int)$data['no'];
        $username = $_SESSION['username'];
        $retval = ['status' => false];

        if(!$this->is_writer($no, $username))
            return json_encode($retval);

        if($this->query('DELETE', ['table' => 'videos', 'no' => $no]))
            $retval['status'] = true;
        
        return json_encode($retval);
    }

    private function parse_info($video_id) {
        $url = "https://www.youtube.com/get_video_info?video_id={$video_id}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        ob_start();
        curl_exec($ch);
        $contents = ob_get_contents();
        ob_end_clean();

        preg_match("/player_response\=(.*%7D)&/mis", $contents, $matches);
        $result = json_decode(urldecode($matches[1]), true);
        
        return $result['videoDetails'];
    }

    private function get_video_info($url) {
        $retval = ['status' => false];
        $youtube_regex = '/https:\/\/(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/mis';
        if(!preg_match($youtube_regex, $url, $matches)) {
            $retval['error'] = 'Invalid url format!';
            return $retval;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        ob_start();
        curl_exec($ch);
        $contents = ob_get_contents();
        ob_end_clean();

        if(strlen($contents) == 0) {
            $retval['error'] = 'This video does not exist.';
            return $retval;
        }
        else if(!$video_info = $this->parse_info($matches[1])) {
            $retval['error']  = "Unknown error :".PHP_EOL;
            $retval['error'] .= escape_for_json($contents);
            return $retval;
        }

        $retval['status'] = true;
        $video_info = array_map('addslashes', $video_info);
        $retval['result'] = [
            'video_id' => $video_info['videoId'],
            'video_title' => $video_info['title'],
            'video_length' => $video_info['lengthSeconds'],
            'video_thumbnail' => $video_info['thumbnail']['thumbnails'][3]['url'],
        ];
        return $retval;
    }

    public function get_last_no() {
        $videos = $this->query('SELECT', ['table' => 'videos']);
        if(!$videos || $videos == [])
            return 0;
        $last_video = array_pop($videos);
        return (int)$last_video['no'];
    }
    
    private function is_writer($no, $username) {
        $query = [
            'table' => 'videos',
            'no' => $no,
        ];
        $result = $this->query('SELECT', $query)[0];
        $writer = $result['username'];

        if($writer === $username)
            return true;

        return false;
    }
}

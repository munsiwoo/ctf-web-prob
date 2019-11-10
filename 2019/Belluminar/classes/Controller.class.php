<?php
require_once __DIR__.'/AlephDB/Aleph.class.php';
require_once __DIR__.'/Render.class.php';
require_once __DIR__.'/User.class.php';
require_once __DIR__.'/Videos.class.php';
require_once __DIR__.'/MunTemplate.class.php';

class Controller {
    function __construct($http_method, $request_uri, $is_login) {
        $User = new User();
        $Render = new Render();
        $Videos = new Videos();
        $MunTemplate = new MunTemplate(__TEMPLATE__); 

        if($is_login && !$User->is_user()) {
            session_destroy();
            redirect_url('/');
        }

        if(in_array($request_uri, ['/static', '/static/'])) {
            $file = $_SERVER['DOCUMENT_ROOT'].'/static/'.$_GET['file'];

            if(!isset($_GET['file']) || !file_exists($file))
                die('File not found : '.htmlspecialchars($file));
            if(substr_count($_GET['file'], '/') > 2)
                die('Hack Detected!');
            
            $ext = pathinfo(basename($file))['extension'];

            if(in_array($ext, ['png', 'jpg', 'gif']))
                header('Content-Type: image');
            
            die(file_get_contents($file));
        }

        if($http_method == 'GET') {
            $top_menu = $Render->top_menu($is_login);
            $MunTemplate->render_template('header.html', ['top_menu'=>$top_menu]);
        }

        switch($request_uri) {
            case '/' :
                if($is_login)
                    $msg = 'Hello, '.htmlspecialchars($_SESSION['username']);
                else
                    $msg = 'Welcome to AlephpHub!';
                $MunTemplate->render_template('index.html', ['msg'=>$msg]);
                break;

            case '/logout' :
                session_destroy();
                redirect_url('/');
                break;

            case '/login' :
                if($http_method == 'POST') {
                    echo $User->login($_POST);
                    break;
                }
                $MunTemplate->render_template('login.html');
                break;

            case '/register' :
                if($http_method == 'POST') {
                    echo $User->register($_POST);
                    break;
                }
                $MunTemplate->render_template('register.html');
                break;

            case '/videos' :
                $videos = $Render->all_videos();
                $MunTemplate->render_template('videos.html', ['videos' => $videos]);
                break;

            case '/mypage' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                
                $username = htmlspecialchars($_SESSION['username']);
                $user_info = $Render->get_user_info();
                $is_premium = $User->is_premium();

                $render_data = [
                    'username' => $username,
                    'attendance' => $user_info['attendance'],
                    'joined' => $user_info['joined'],
                    'is_premium' => $is_premium ? 'Y' : 'N',
                ];

                $MunTemplate->render_template('mypage.html', $render_data);
                break;

            case '/upload' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                if(!$User->is_premium())
                    redirect_url("/", "You are not premium.");
                if($http_method == 'POST') {
                    echo $Videos->upload($_POST);
                    break;
                }
                $MunTemplate->render_template('upload.html');
                break;

            case '/read' :
                if(!$_GET['no'])
                    redirect_url("/videos", "It's a wrong approach.");
                $post = $Videos->read($_GET);
                if($post['status']) {
                    $is_writer = ($post['result']['username'] === $_SESSION['username']);
                    $render_data = array_map('htmlspecialchars', [
                        'no' => $post['result']['no'],
                        'title' => $post['result']['title'],
                        'writer' => $post['result']['username'],
                        'contents' => $post['result']['contents'],
                        'is_writer' => $is_writer,
                    ]);
                    $render_data['video'] = json_decode($post['result']['video'], true);
                    $MunTemplate->render_template('read.html', $render_data);
                }
                else
                    redirect_url("/videos", "It's a wrong approach.");
                break;

            case '/update' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                if($http_method == 'POST') {
                    echo $Board->update($_POST);
                    break;
                }

                if(!$_GET['no'])
                    redirect_url("/board", "It's a wrong approach.");

                $post = $Render->get_post($_GET['no'], $_SESSION['username']);
                if($post['status'])
                    $MunTemplate->render_template('update.html', ['post'=>$post['result']]);
                else 
                    redirect_url("/board", "It's a wrong approach.");
                break;

            case '/delete' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                if($http_method == 'POST') {
                    echo $Videos->delete($_POST);
                    break;
                }
                break;

            case '/check_attendance' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                if($http_method == 'POST')
                    echo $User->check_attendance();
                break;

            case '/get_premium' :
                if(!$is_login)
                    redirect_url("/login", "Please login!");
                if($http_method == 'POST')
                    echo $User->get_premium();
                break;

            default :
                header("HTTP/1.1 404 Not Found");
                echo '<br><img src="/static?file=img/404.png" style="width: 500px; height: auto;">';
        }

        if($http_method == 'GET') {
            $MunTemplate->render_template('footer.html');
        }
    }
}

<?php
function redirect_url($url, $msg="") {
    $execute  = "<script>location.href=\"{$url}\";";
    $execute .= strlen($msg) ? 'alert("'.addslashes($msg).'");' : '';
    $execute .= '</script>'; die($execute);
}

function backward_url($msg="") {
    $execute  = '<script>history.back();';
    $execute .= strlen($msg) ? 'alert("'.addslashes($msg).'");' : '';
    $execute .= '</script>'; die($execute);
}

function process_password($password) {
    return md5(hash('sha256', sha1(md5($password).__SALT__)));
}

function strtolower_callback($argv) {
    return strtolower("\x{$argv[1]}");
}

function escape_for_json($argv) {
    $argv = urlencode($argv);
    return preg_replace_callback('/\%(.{2})/', 'strtolower_callback', $argv);
}
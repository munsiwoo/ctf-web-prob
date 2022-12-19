<?php
error_reporting(0);
if(isset($_GET['source'])){
    show_source('lucky.php');
    exit;
}
$action = isset($_GET['action']) ? $_GET['action'] : 'run';
$jscode = isset($_GET['jscode']) ? $_GET['jscode'] : 'print()';

$seven = '[\w()`\'".{}\[\]_+;\/<>]{8}'; // max length is 7, alert(1) -> dead
$common = '[$&#;]|flag|name|eval|script|frame|on.{5}'; // common filter keywords
if(preg_match("/{$seven}|{$common}/is", $jscode)) $jscode = 'print()';

if($action == 'run')
    echo '<script>'.$jscode.'</script>';
if($action == 'show')
    echo $jscode;
?>
<a href="?source">source</a>
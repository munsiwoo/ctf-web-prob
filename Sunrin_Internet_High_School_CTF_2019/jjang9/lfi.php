<?php
error_reporting(0);
# can you local file inclusion?
# made by munsiwoo

if($_GET['f']) {
    $f = basename($_GET['f']);
    if(file_exists($f)) die('hacking hazimaseyo!');

    include $f;
}

show_source(__FILE__);

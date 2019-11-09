<?php
# made by munsiwoo
error_reporting(0);
include 'config.php';

if(isset($_GET['phpinfo'])){
	php_info();
}

if(isset($_GET['eval'])){
    $filter = '/_|(.*)(\'|\"|\`|\()(.*)(\'|\"|\`|\))|(.php|\=|\$)/i';
    if(preg_match($filter, $_GET['eval'])){
		exit('nope');
    }
    # 403 forbidden : system filter
    eval($_GET['eval']);
}

echo '<hr>';
highlight_file(__FILE__);
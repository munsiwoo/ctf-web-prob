<?php
# config.php
error_reporting(0);

function php_info(){
    exit('PHP Version 7.0.18-0ubuntu0.16.10.1');
}

$filter = "/\||\/|\.\.|config|fwrite|fputs|shutdown|halt|".
"reboot|init|rm|mv|cp|remove|rename|copy|grep|nc|unlink|find|".
"apt|yum|passwd|chmod|chown|ln|kill|lilo|ssh|telnet/i";

$implode = implode($_REQUEST);

if(preg_match($filter, $implode)){
	exit('403 forbidden');
}
?>
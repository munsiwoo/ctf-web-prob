<?php
error_reporting(0);
require_once 'config.php';

$origin = get_origin_structure($db);
$table = $origin['table'];
$column = $origin['column'];
$data = $origin['data'];

$id = pig_waf($_POST['id']);
$pw = pig_waf($_POST['pw']);
$retval = ['result' => false, 'message' => 'Failed.'];

$sql = "select * from {$table} where {$column['id']}='{$id}' and {$column['pw']}='{$pw}';";
if(!$result = $db->query($sql)) {
    $retval['message'] = 'query error';
    die(json_encode($retval));
}

if($fetch = $result->fetch_array(MYSQLI_NUM)) {
    $retval['result'] = true;
    $retval['message'] = "Hello, {$fetch[0]}\n";

    if($fetch[0] == 'admin')
        $retval['message'] .= 'Can you read my note? zz';
    else
        $retval['message'] .= "Note : {$fetch[2]}";
}

clean_structure($db, $origin);
echo json_encode($retval);
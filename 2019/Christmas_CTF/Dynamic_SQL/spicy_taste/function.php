<?php
function generate_random() {
    $strings = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($strings);
    return substr(implode('', $strings), 0, mt_rand(7, 14));
}

function pig_waf($argv) {
    if(preg_match('/dynamic_|file|mysql/is', $argv))
        die(json_encode(['message'=>'Waf has detected a hack!']));
    if(preg_match('/\x27|\x20|\x09|\x0a|\x0b|\x0c|\x0d|\xa0|\//is', $argv))
        die(json_encode(['message'=>'Waf has detected a hack!']));
    if(preg_match('/(?:[\s`]+)?information_schema(?:[\s`]+)?\.(?:[\s`]+)?([\w]+)/is', $argv))
        die(json_encode(['message'=>'Waf has detected a hack!']));
    return $argv;
}

function create_db($host, $user, $pass, $dbname) {
    global $admin_pw, $flag;
    $dbname = 'dynamic_'.$dbname;

    $db = new mysqli($host, $user, $pass, 'mysql');
    $db->query("create database {$dbname};");
    $db->close();

    $db = new mysqli($host, $user, $pass, $dbname);
    $table = generate_random();
    $column['id'] = generate_random();
    $column['pw'] = generate_random();
    $column['note'] = generate_random();

    $db->query("create table {$table} (
        {$column['id']} varchar(32),
        {$column['pw']} varchar(32),
        {$column['note']} varchar(256)
    );");
    
    $db->query("insert into {$table} values ('admin', '{$admin_pw}', '{$flag}');");
    $db->query("insert into {$table} values ('guest', 'guest', 'im guest');");
    $db->close();

    return  $dbname;
}

function get_origin_structure($db) {
    $result = $db->query('show tables;');
    $origin_table = $result->fetch_array(MYSQLI_NUM)[0];

    $result = $db->query("desc {$origin_table};");
    while($fetch = $result->fetch_array(MYSQLI_NUM)) {
        $columns[] = $fetch[0];
    }

    $origin_column['id'] = $columns[0];
    $origin_column['pw'] = $columns[1];
    $origin_column['note'] = $columns[2];

    $index = 0;
    $result = $db->query("select * from {$origin_table};");
    while($fetch = $result->fetch_array(MYSQLI_NUM)) {
        foreach($fetch as $row) {
            $origin_data[$index][] = $row;
        }
        $index++;
    }

    return [
        'table' => $origin_table,
        'column' => $origin_column,
        'data' => $origin_data
    ];
}

function clean_structure($db, $origin) {
    global $admin_pw, $flag;

    $new_table = generate_random();
    $new_column['id'] = generate_random();
    $new_column['pw'] = generate_random();
    $new_column['note'] = generate_random();

    if($db->query("drop table {$origin['table']};")) {
        $db->query("create table {$new_table} (
            {$new_column['id']} varchar(32),
            {$new_column['pw']} varchar(32),
            {$new_column['note']} varchar(256)
        );");

        $db->query("insert into {$new_table} values ('admin', '{$admin_pw}', '{$flag}');");
        $db->query("insert into {$new_table} values ('guest', 'guest', 'im guest');");
        return true;
    }

    return false;
}
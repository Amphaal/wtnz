<?php

define('USER_DB_PATH', dirname(__DIR__) . "/data/users.json");
define('USERS_DATA_RPATH', "/data/users");
define('USERS_DATA_PATH', dirname(__DIR__) . USERS_DATA_RPATH);

function updateUserDb($new_file) {
    mkdir(dirname(USER_DB_PATH));   
    $new_file = json_encode($new_file, JSON_PRETTY_PRINT);
    file_put_contents(USER_DB_PATH, $new_file);
}

function getUserDb() {
    
    $db = null;
    $file_content = @file_get_contents(USER_DB_PATH);
    
    if($file_content === FALSE) {
        $db = json_decode('{}', true);
        updateUserDb($db);
    }

    if(!isset($db)) {
        $db = json_decode($file_content, true);
    }

    return $db;
}
<?php

define('USER_DB_PATH', dirname(__DIR__) . "/data/users.json");
define('USERS_DATA_RPATH', getRelativeRootApp() . "data/users");
define('USERS_DATA_PATH', dirname(__DIR__) . "/data/users");


function getInternalUserFolder($user) {
    $path = USERS_DATA_PATH . '/' . $user . '/';
    return $path;
}

function getPublicUserFolder($user) {
    $path = USERS_DATA_RPATH . '/' . $user . '/';
    return $path;
}

function updateUserDb($new_file) {
    mkdir(dirname(USER_DB_PATH));   
    $new_file = json_encode($new_file, JSON_PRETTY_PRINT);
    file_put_contents(USER_DB_PATH, $new_file);
}

function updateUsersConfig($myNewConfig, $user = null) {
    if($user == null) $user = getCurrentUserLogged();
    
    $users = ObtainUsersDatabaseFileAsObject();
    $users[$user] =  $myNewConfig;
    updateUserDb($users);
    Config::forceUpdate();
}

function getMyConfig() {
   if(isUserLogged()) {
        return getUserConfig(getCurrentUserLogged());
    }
}

function getAllAppUsers() {
    return Config::get()['users'] ?? null;
}

function getUserConfig($user) {
    return getAllAppUsers()[$user] ?? null;
}

function ObtainUsersDatabaseFileAsObject() {
    
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
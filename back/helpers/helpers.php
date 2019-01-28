<?php

function getQueryString() {
    $request_uri = explode('/', $_SERVER['REQUEST_URI']);
    $request_uri = array_filter($request_uri, 'strlen' );
    array_shift($request_uri);
    return $request_uri;
}

function errorOccured($error_text) {
    if(isset($_POST['headless'])) http_response_code(520);
    exit($error_text); 
    //throw new Exception($error_text);
}

function mayCreateUserDirectory($directory) {
    $shouldWrite = !file_exists($directory);
    if (!$shouldWrite) return null;

    $result = mkdir($directory, 0777, true);
    if (!$result) 
    {
        errorOccured(i18n("e_wdu", $directory));
    }
}

function checkUserSpecificFolders() {
    //for each user
    foreach(WTNZ_CONFIG['users'] as $user => $pass) {
        $path = formatUserDataFolder($user);
        mayCreateUserDirectory($path);
    }
}  

function formatUserDataFolder($user) {
    $usersFolder = WTNZ_CONFIG['path_to_users_data_folder'];
    $path = $usersFolder . $user . '/';
    return $path;
}

function checkUserExists($user) {
    $do_exist = isset(WTNZ_CONFIG['users'][$user]) && file_exists(formatUserDataFolder($user));
    if(!$do_exist) errorOccured(i18n("e_unsu", $user));
}

function comparePasswords($user) {
    $passwd = isset($_POST['password']) ? $_POST['password'] : NULL;
    if(empty($passwd)) errorOccured(i18n("e_nopass"));
    if($passwd != WTNZ_CONFIG['users'][$user]["password"]) errorOccured(i18n("e_pmm"));
}

function testUploadedFile($expectedFilename){
    $fileToUpload = isset($_FILES[$expectedFilename]) ? $_FILES[$expectedFilename] : NULL;
    if(empty($fileToUpload)) errorOccured(i18n("e_upLibMiss"));
    if($fileToUpload['error'] == 4 ) errorOccured(i18n("e_noFUp"));
    if($fileToUpload['error'] > 0 ) errorOccured(i18n("e_upErr"));
}

function uploadFile($pathTo, $expectedFilename) {
        $uploadResult = move_uploaded_file($_FILES[$expectedFilename]['tmp_name'], $pathTo);
        if(!$uploadResult) errorOccured(i18n("e_upErr"));
}

function testFileCompatibility($expectedFilename) {
    $fileContent = file_get_contents($_FILES[$expectedFilename]['tmp_name']);
    
    //check if JSON compliant
    $result = json_decode($fileContent);
    if (json_last_error() !== JSON_ERROR_NONE) errorOccured(i18n("e_ucJSON"));
}

function isUselessUpload($targetPath, $expectedFilename) {
    //check for duplicate in current / uploaded file
    if (!file_exists($targetPath)) return false;
    $hash_uploaded = hash_file('sha1',$_FILES[$expectedFilename]['tmp_name']);
    $hash_current = hash_file('sha1', $targetPath);
    return $hash_uploaded == $hash_current ? true : false;
}

function getCurrentLibraryFileName() {
    return 'current.json';
}

function getCurrentShoutFileName() {
    return 'shout.json';
}

function getFileUploadLimit(){
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}


function getFilesInFolder($path_to) {
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));
    $ret = [];
    foreach($files as $file) { 
        array_push($ret, $path_to . '/' . $file);
    }
    return $ret;
}

//
//conectivity
//

function connectAs($user, $passwd) {
    $ret = array("isError" => true, "description" => null);
    
    if(empty($user)) {
        $ret["description"] = i18n("e_log_nouser");
    }
    elseif(empty($passwd))  {
        $ret["description"] = i18n("e_nopass");
    }
    if(isset($_SESSION["loggedAs"]) && $_SESSION["loggedAs"] == $user) {
        $ret["isError"] = false;
        $ret["description"] = i18n("e_log_identical");
    }
    elseif($passwd != WTNZ_CONFIG['users'][$user]["password"]) {
        $ret["description"] = i18n("e_pmm");
    }
    else {
        $ret["isError"] = false;
        $_SESSION["loggedAs"] = $user;
    }
    
    return $ret;
} 
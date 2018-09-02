<?php

function getQueryString() {
    $request_uri = explode('/', $_SERVER['REQUEST_URI']);
    $request_uri = array_filter($request_uri, 'strlen' );
    array_shift($request_uri);
    return $request_uri;
}

function errorOccured($error_text) {
    if($_POST['headless']) http_response_code(520);
    exit($error_text); 
    //throw new Exception($error_text);
}

function mayCreateUserDirectory($directory) {
    $shouldWrite = !file_exists($directory);
    if (!$shouldWrite) return null;

    $result = mkdir($directory, 0777, true);
    if (!$result) 
    {
        errorOccured('Cannot write directory "' . $directory .'" for the specified user.');
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
    if(!$do_exist) errorOccured('User "' . $user . '" is not set up properly or does not exist');
}

function comparePasswords($user) {
    $passwd = isset($_POST['password']) ? $_POST['password'] : NULL;
    if(empty($passwd)) errorOccured('No password provided, upload is impossible.');
    if($passwd != WTNZ_CONFIG['users'][$user]) errorOccured('Password missmatch, upload is impossible.');
}

function testUploadedFile(){
    $fileToUpload = isset($_FILES['wtnz_file']) ? $_FILES['wtnz_file'] : NULL;
    if(empty($fileToUpload)) errorOccured('Cannot localizate library file in the upload.');
    if($fileToUpload['error'] == 4 ) errorOccured('No file have been uploaded.');
    if($fileToUpload['error'] > 0 ) errorOccured('An issue has occured during the upload.');
}

function testFileCompatibility() {
    $fileContent = file_get_contents($_FILES['wtnz_file']['tmp_name']);
    
    //check if JSON compliant
    $result = json_decode($fileContent);
    if (json_last_error() !== JSON_ERROR_NONE) errorOccured('The uploaded file is not JSON compliant.');
}

function isUselessUpload($targetPath) {
    //check for duplicate in current / uploaded file
    if (!file_exists($targetPath)) return false;
    $hash_uploaded = hash_file('sha1',$_FILES['wtnz_file']['tmp_name']);
    $hash_current = hash_file('sha1', $targetPath);
    return $hash_uploaded == $hash_current ? true : false;
}

function getCurrentLibraryFileName() {
    return 'current.json';
}

function getFileUploadLimit(){
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}
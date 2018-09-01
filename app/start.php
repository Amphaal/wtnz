<?php

include_once "helpers.php";
include_once "config.php";

init_app();

function init_app() {
    
    checkUserSpecificFolders(); 
    
    $qs = getQueryString();
    $user_qs = array_shift($qs);

    //if no user directory is being accessed
    if(!isset($user_qs)) return accessIndex();

    //check if user exists
    checkUserExists($user_qs);

    //if upload 
    if(!empty($_POST) && !empty($_FILES)) return tryUpload($user_qs);

    //if manual upload
    $action = array_shift($qs);
    if($action == 'upload') accessManualUploadUI($user_qs);

    //else
    return accessUserLibrary($user_qs);
}

function accessIndex() {
    echo "helo";
}

function tryUpload($user_qs) {
    comparePasswords($user_qs);
    testUploadedFile();
    testFileCompatibility();
    processUploadedFile($user_qs);
}

function processUploadedFile($user_qs) {

    $fileServerName = getCurrentLibraryFileName();
    $pathTo = formatUserDataFolder($user_qs) . $fileServerName;
    
    //check for duplicates
    if(isUselessUpload($pathTo)) exit("File identical to current, no upload needed.");

    //move the file to user's directory
    $uploadResult = move_uploaded_file($_FILES['wtnz_file']['tmp_name'], $pathTo);
    if(!$uploadResult) errorOccured('Issue while uploading file.');
    
    //copy save
    $archive_dir = time().'_'.rand(0,999);
    $copyDestination = formatUserDataFolder($user_qs) . $archive_dir . '/' . $fileServerName;
    
    if (!mkdir(dirname($copyDestination))) errorOccured('Error while creating archive directory.');
    if (!copy($pathTo, $copyDestination)) errorOccured('Error while copying uploaded file to archive directory.');
    
    return accessUserLibrary($user_qs, true);
}


function accessUserLibrary($user_qs, $skip_auto_redirect = false) {
    $expectedLibrary = formatUserDataFolder($user_qs) . getCurrentLibraryFileName();
    if(!$skip_auto_redirect && !file_exists($expectedLibrary)) return accessManualUploadUI($user_qs); //redirect if no file

    echo file_get_contents($expectedLibrary);
}

function accessManualUploadUI($user_qs) {
    include "ui_templates/upload.php";
}

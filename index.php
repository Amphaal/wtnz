<?php

include_once "config/config.php";
include_once "back/helpers/helpers.php";

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
    if($action == 'upload') return accessManualUploadUI($user_qs);

    //else
    return accessUserLibrary($user_qs);
}

function accessIndex() {
    include "back/ui_templates/home.php";
}

function tryUpload($user_qs) {
    comparePasswords($user_qs);
    testUploadedFile();
    testFileCompatibility();
    processUploadedFile($user_qs);
}

function processUploadedFile($user_qs) {

    $pathTo = formatUserDataFolder($user_qs) . getCurrentLibraryFileName();
    
    //check for duplicates
    if(isUselessUpload($pathTo)) exit("File identical to current, no upload needed.");

    //archive current file if necessary
    archivePreviousUpload($user_qs, $pathTo);

    //move the uploaded file to user's directory
    $uploadResult = move_uploaded_file($_FILES['wtnz_file']['tmp_name'], $pathTo);
    if(!$uploadResult) errorOccured('Issue while uploading file.');

    //specific redirect for headless client
    if(isset($_POST['headless'])) exit('Bon appétit!');
    
    //redirect to users library...
    header("Location: " . dirname($_SERVER['REQUEST_URI']));
    exit();
}

function archivePreviousUpload($user_qs, $pathTo) {
    //ignore if current file doesnt exist
    if(!file_exists($pathTo)) return;

    //copy save
    $archive_dir = filemtime($pathTo).'_'.rand(0,999);
    $copyDestination = formatUserDataFolder($user_qs) . $archive_dir . '/' . basename($pathTo);
    
    //archive...
    if (!mkdir(dirname($copyDestination))) errorOccured('Error while creating archive directory.');
    if (!copy($pathTo, $copyDestination)) errorOccured('Error while copying uploaded file to archive directory.');
}

function accessManualUploadUI($user_qs) {
    include "back/ui_templates/upload.php";
}

function accessUserLibrary($user_qs, $skip_auto_redirect = false) {
    $expectedLibrary = formatUserDataFolder($user_qs) . getCurrentLibraryFileName();

    //ask to upload if no file
    if(!$skip_auto_redirect && !file_exists($expectedLibrary)) return accessManualUploadUI($user_qs);

    //Client variables
    $clientURLLibrary = dirname($_SERVER['REQUEST_URI']) . substr($expectedLibrary, 1);
    $latestUpdate = filemtime($expectedLibrary);

    include "front/home.php";
}

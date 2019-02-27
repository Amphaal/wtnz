<?php

function uploadLib($user_qs) {
    $expectedFilename = 'wtnz_file';

    comparePasswords($user_qs);
    testUploadedFile($expectedFilename);
    testFileCompatibility($expectedFilename);
    processUploadedLib($user_qs, $expectedFilename);
}

function processUploadedLib($user_qs, $expectedFilename) {

    $pathTo = getInternalUserFolder($user_qs) . getCurrentLibraryFileName();
    
    //check for duplicates
    if(isUselessUpload($pathTo, $expectedFilename)) exit(i18n("fiNu"));

    //archive current file if necessary
    //archivePreviousUpload($user_qs, $pathTo);

    //move the uploaded file to user's directory
    uploadFile($pathTo, $expectedFilename);

    //generate data from upload
    $dg = new DataGenerator($user_qs);
    $dg->generateUnifiedFile();

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
    $copyDestination = getInternalUserFolder($user_qs) . $archive_dir . '/' . basename($pathTo);
    
    //archive...
    if (!mkdir(dirname($copyDestination))) errorOccured(i18n("e_cad"));
    if (!copy($pathTo, $copyDestination)) errorOccured(i18n("e_cufad"));
}

///
/// UI specifics
///

function routerUploadLib($user_qs, $action) {

    $isAPICall = isset($_POST['headless']);
    if(!$isAPICall) {
        //redirect to upload UI if no library for the user
        $expectedLibrary = getInternalUserFolder($user_qs) . getCurrentLibraryFileName();
        if(!file_exists($expectedLibrary)) return accessManualUploadUI($user_qs);
    }

    //if not asking lib upload, skip for next router
    if($action != 'uploadlib') return;
    
    //check prerequisites
    if(!empty($_POST) && !empty($_FILES)) {
        return uploadLib($user_qs);  
    // if from UI 
    } elseif($isAPICall) {
        errorOccured(i18n("missingArgs"));
    } else {
        return accessManualUploadUI($user_qs);
    }

}

function accessManualUploadUI($user_qs) {
    include "back/ui/upload.php";
    exit;
}

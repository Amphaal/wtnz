<?php

function uploadLib($user_qs) {
    comparePasswords($user_qs);
    testUploadedFile(constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
    testFileCompatibility(constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
    processUploadedLib($user_qs, constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
}

function processUploadedLib($user_qs, $expectedFilename) {

    $pathTo = getInternalUserFolder($user_qs) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    
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
    $copyDestination = getInternalUserFolder($user_qs) . $archive_dir . "/" . basename($pathTo);
    
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
        $expectedLibrary = getInternalUserFolder($user_qs) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
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
    includeXMLRSwitch("layout/admin/components/upload.php", get_defined_vars());
}

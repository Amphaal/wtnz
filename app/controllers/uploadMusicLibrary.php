<?php

function uploadMusicLibrary($user_qs) {
    checkPOSTedUserPassword($user_qs);
    testUploadedFile(constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
    testUploadedFileJSONCompliance(constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
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

function routerUploadMusicLibrary($user_qs, $action) {
    // if having POST && FILES, means we try to upload
    if (!empty($_POST) && !empty($_FILES)) {
        return uploadMusicLibrary($user_qs);  
    }

    /** check if calling from COMPANION_APP */
    $isAPICall = isset($_POST['headless']);

    /** if called from API, always expect POST and FILES to be filled */
    if($isAPICall) {
        errorOccured(i18n("missingArgs"));
    }

    // redirect to upload UI if no library for the user OR wanting explicitely this UI
    $expectedLibrary = getInternalUserFolder($user_qs) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    if(!file_exists($expectedLibrary) || $action == 'uploadMusicLibrary') {
        return injectAndDisplayIntoAdminLayout("layout/admin/components/upload.php", get_defined_vars());
    }
}

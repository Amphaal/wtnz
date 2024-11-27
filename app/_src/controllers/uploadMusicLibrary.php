<?php

function uploadMusicLibrary(string $sourcePhpRoot, $request, $qs_user) {
    checkPOSTedUserPassword($sourcePhpRoot, $request, $qs_user);
    testUploadedFile($request, constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
    prepareAndTestUploadedFileCompliance($request, constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
    processUploadedMusicLibrary($sourcePhpRoot, $request, $qs_user, constant("MUSIC_LIB_UPLOAD_FILE_NAME"));
}

function processUploadedMusicLibrary(string $sourcePhpRoot, $request, $qs_user, $expectedFilename) {

    $pathTo = getInternalUserFolder($sourcePhpRoot, $qs_user) . constant("MUSIC_LIB_PROFILE_FILE_NAME");

    //check for duplicates
    if(isUselessUpload($request, $pathTo, $expectedFilename)) {
        ContextManager::get("exit",
            ContextManager::get("i18n")("fiNu")
        );
        return;
    }

    //archive current file if necessary
    //archivePreviousUpload($request, $qs_user, $pathTo);

    //move the uploaded file to user's directory
    uploadFile($request, $pathTo, $expectedFilename);

    //generate data from upload
    $dg = new DataGenerator($sourcePhpRoot, $qs_user);
    $dg->generateUnifiedFile();

    //specific redirect for headless client
    if(isset($request->post['headless'])) {
        ContextManager::get("exit")('Bon appétit!');
        return;
    }
    
    //redirect to users library...
    ContextManager::get("header")("Location: " . dirname($request->server['request_uri']));
    ContextManager::get("exit");
}

function archivePreviousUpload(string $sourcePhpRoot, $request, $qs_user, $pathTo) {
    //ignore if current file doesnt exist
    if(!file_exists($pathTo)) return;

    //copy save
    $archive_dir = filemtime($pathTo).'_'.rand(0,999);
    $copyDestination = getInternalUserFolder($sourcePhpRoot, $qs_user) . $archive_dir . "/" . basename($pathTo);
    
    //archive...
    if (!mkdir(dirname($copyDestination))) errorOccured($request, ContextManager::get("i18n")("e_cad"));
    if (!copy($pathTo, $copyDestination)) errorOccured($request, ContextManager::get("i18n")("e_cufad"));
}

///
/// UI specifics
///

function routerMiddleware_UploadMusicLibrary(string $sourcePhpRoot, $request, $qs_user, $wantsExplicitAccess) {
    // if having POST && FILES, means we try to upload
    if (!empty($request->post) && !empty($request->files)) {
        return uploadMusicLibrary($sourcePhpRoot, $request, $qs_user);  
    }

    /** check if calling from COMPANION_APP */
    $isAPICall = isset($request->post['headless']);

    /** if called from API, always expect POST and FILES to be filled */
    if($isAPICall) {
        errorOccured($request, ContextManager::get("i18n")("missingArgs"));
    }

    // redirect to upload UI if no library for the user OR wanting explicitely this UI
    $expectedLibrary = getInternalUserFolder($sourcePhpRoot, $qs_user) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    if(!file_exists($expectedLibrary) || $wantsExplicitAccess) {
        return ContextManager::get("injectAndDisplayIntoAdminLayout")($request, "layout/admin/components/upload.php", get_defined_vars());
    }
}

<?php

function uploadShout($user_qs) {
    // preliminary tests
    checkPOSTedUserPassword($user_qs);
    testUploadedFile(constant("SHOUT_UPLOAD_FILE_NAME"));
    prepareAndTestUploadedFileCompliance(constant("SHOUT_UPLOAD_FILE_NAME"));
    
    // uploading file
    $whereToUpload = getInternalUserFolder($user_qs) . constant("SHOUT_PROFILE_FILE_NAME");
    uploadFile($whereToUpload, constant("SHOUT_UPLOAD_FILE_NAME"));

    //
    exit(i18n("shouted"));
}

function rerouteToUploadShout($user_qs, $action) {
    $isAPICall = isset($_POST['headless']);
    if(!$isAPICall) return;

    //check prerequisites
    if(!empty($_POST) && !empty($_FILES)) {
        return uploadShout($user_qs);  
    } else {
        errorOccured(i18n("missingArgs"));
    }
}
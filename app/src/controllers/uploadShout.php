<?php

function uploadShout($qs_user) {
    // preliminary tests
    checkPOSTedUserPassword($qs_user);
    testUploadedFile(constant("SHOUT_UPLOAD_FILE_NAME"));
    prepareAndTestUploadedFileCompliance(constant("SHOUT_UPLOAD_FILE_NAME"));
    
    // uploading file
    $whereToUpload = getInternalUserFolder($qs_user) . constant("SHOUT_PROFILE_FILE_NAME");
    uploadFile($whereToUpload, constant("SHOUT_UPLOAD_FILE_NAME"));

    //
    exit(i18n("shouted"));
}

function routerInterceptor_uploadShout($qs_user) {
    //
    $isAPICall = isset($request->post['headless']);
    if(!$isAPICall) {
        http_response_code(500);
        die('expects API call');
    }

    //check prerequisites
    if(!empty($request->post) && !empty($request->files)) {
        return uploadShout($qs_user);  
    } else {
        errorOccured(i18n("missingArgs"));
    }
}
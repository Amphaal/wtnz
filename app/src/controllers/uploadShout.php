<?php

function uploadShout($request, $qs_user) {
    // preliminary tests
    checkPOSTedUserPassword($request, $qs_user);
    testUploadedFile($request, constant("SHOUT_UPLOAD_FILE_NAME"));
    prepareAndTestUploadedFileCompliance($request, constant("SHOUT_UPLOAD_FILE_NAME"));
    
    // uploading file
    $whereToUpload = getInternalUserFolder($qs_user) . constant("SHOUT_PROFILE_FILE_NAME");
    uploadFile($request, $whereToUpload, constant("SHOUT_UPLOAD_FILE_NAME"));

    //
    exit($i18n("shouted"));
}

function routerInterceptor_uploadShout($request, $qs_user) {
    //
    $isAPICall = isset($request->post['headless']);
    if(!$isAPICall) {
        http_response_code(500);
        die('expects API call');
    }

    //check prerequisites
    if(!empty($request->post) && !empty($request->files)) {
        return uploadShout($request, $qs_user);  
    } else {
        errorOccured($request, $i18n("missingArgs"));
    }
}
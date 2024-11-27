<?php

function uploadShout($qs_user) {
    // preliminary tests
    checkPOSTedUserPassword($qs_user);
    testUploadedFile(SHOUT_UPLOAD_FILE_NAME);
    prepareAndTestUploadedFileCompliance(SHOUT_UPLOAD_FILE_NAME);
    
    // uploading file
    $whereToUpload = getInternalUserFolder($qs_user) . SHOUT_PROFILE_FILE_NAME;
    uploadFile($whereToUpload, SHOUT_UPLOAD_FILE_NAME);

    //
    ContextManager::get("exit",
        i18n("shouted")
    );
}

function routerInterceptor_uploadShout($qs_user) {
    //
    $request = ContextManager::get("REQUEST");
    $isAPICall = isset($request->post['headless']);
    if(!$isAPICall) {
        ContextManager::get("http_response_code")(500);
        ContextManager::get("exit")('expects API call');
        return;
    }

    //check prerequisites
    if(!empty($request->post) && !empty($request->files)) {
        return uploadShout($qs_user);  
    } else {
        errorOccured(i18n("missingArgs"));
    }
}
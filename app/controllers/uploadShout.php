<?php

function uploadShout($user_qs) {
    //tests
    checkPOSTedUserPassword($user_qs);
    testUploadedFile(constant("SHOUT_UPLOAD_FILE_NAME"));
    testUploadedFileJSONCompliance(constant("SHOUT_UPLOAD_FILE_NAME"));
    
    //move file
    uploadFile(getInternalUserFolder($user_qs) . constant("SHOUT_PROFILE_FILE_NAME"), constant("SHOUT_UPLOAD_FILE_NAME"));
    
    exit(i18n("shouted"));
}

function routerUploadShout($user_qs, $action) {
    if($action != 'uploadshout') return;

    $isAPICall = isset($_POST['headless']);
    if(!$isAPICall) return;

    //check prerequisites
    if(!empty($_POST) && !empty($_FILES)) {
        return uploadShout($user_qs);  
    } else {
        errorOccured(i18n("missingArgs"));
    }
}
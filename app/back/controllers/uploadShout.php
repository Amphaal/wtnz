<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/config/const.php";

function uploadShout($user_qs) {
    //tests
    comparePasswords($user_qs);
    testUploadedFile($expectedShoutFilename);
    testFileCompatibility($expectedShoutFilename);
    
    //move file
    uploadFile(getInternalUserFolder($user_qs) . getCurrentShoutFileName(), $expectedShoutFilename);
    
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
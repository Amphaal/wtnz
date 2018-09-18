<?php

function uploadShout($user_qs) {
    $expectedFilename = 'shout_file';
    
    //tests
    comparePasswords($user_qs);
    testUploadedFile($expectedFilename);
    testFileCompatibility($expectedFilename);
    
    //move file
    uploadFile(formatUserDataFolder($user_qs) . getCurrentShoutFileName(), $expectedFilename);
    
    exit('Shouted !');
}

function routerUploadShout($user_qs, $action) {
    if($action != 'uploadShout') return;

    $isAPICall = isset($_POST['headless']);
    if(!$isAPICall) return;

    //check prerequisites
    if(!empty($_POST) && !empty($_FILES)) {
        return uploadShout($user_qs);  
    } else {
        errorOccured('Missing arguments');
    }
}
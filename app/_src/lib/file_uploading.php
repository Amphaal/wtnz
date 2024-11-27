<?php 

/** generic cleaning of POST fields, if any */
function sanitizePOST() {
    //
    $request = ContextManager::get("REQUEST");

    //
    if($request->post && array_key_exists('username', $request->post)) {
        //
        $request->post['username'] = trim(
            strtolower(
                $request->post['username']
            )
        );

        ContextManager::set("REQUEST", $request);
    }
} 

function getFileUploadLimit() {
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}

function isUselessUpload($targetPath, $expectedFilename) {
    //check for duplicate in current / uploaded file
    if (!file_exists($targetPath)) return false;
    $hash_uploaded = hash_file('sha1', ContextManager::get("REQUEST")->files[$expectedFilename]['tmp_name']);
    $hash_current = hash_file('sha1', $targetPath);
    return $hash_uploaded == $hash_current ? true : false;
}

function testUploadedFile($expectedFilename){
    $filesUploaded = ContextManager::get("REQUEST")->files;
    $fileToUpload = isset($filesUploaded[$expectedFilename]) 
        ? $filesUploaded->files[$expectedFilename] 
        : NULL;
    if(empty($fileToUpload)) errorOccured(i18n("e_upLibMiss"));
    if($fileToUpload['error'] == 4 ) errorOccured(i18n("e_noFUp"));
    if($fileToUpload['error'] > 0 ) errorOccured(i18n("e_upErr"));
}

function uploadFile($pathTo, $expectedFilename) {
    $uploadResult = move_uploaded_file(ContextManager::get("REQUEST")->files[$expectedFilename]['tmp_name'], $pathTo);
    if(!$uploadResult) errorOccured(i18n("e_upErr"));
}

function prepareAndTestUploadedFileCompliance($expectedFilename) {
    $decompressed = '';
    $request = ContextManager::get("REQUEST");
    
    // check if compressed
    if($request->files[$expectedFilename]['type'] == "application/compressed-mlib") {
        //
        $decompressed = zlib_decode(file_get_contents($request->files[$expectedFilename]["tmp_name"]));

        //
        file_put_contents($request->files[$expectedFilename]["tmp_name"], $decompressed);

        //
        $request->files[$expectedFilename]["name"] = pathinfo($request->files[$expectedFilename]["name"], PATHINFO_FILENAME) . ".json";
        $request->files[$expectedFilename]["full_path"] = pathinfo($request->files[$expectedFilename]["name"], PATHINFO_FILENAME) . ".json";
        $request->files[$expectedFilename]["type"] = "application/json";
        $request->files[$expectedFilename]["size"] = strlen($decompressed);

        //
        ContextManager::set("REQUEST", $request);
    }

    //
    if (empty($decompressed)) {
        $decompressed = file_get_contents($request->files[$expectedFilename]['tmp_name']);
    }

    //check if JSON compliant
    json_decode($decompressed);
    if (json_last_error() !== JSON_ERROR_NONE) {
        errorOccured(i18n("e_ucJSON"));
    }
}
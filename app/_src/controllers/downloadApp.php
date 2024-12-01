<?php 

function routerInterceptor_Download($qs_action) {

    $out = function() {
        ob_end_clean(); 
        ContextManager::get("exit")();
    };

    //if xmlHttpRequest, cancel...
    if(isXMLHttpRequest()) {
        $out();
        return;
    }
    
    //target server folder
    $initialPath = getCompanionAppDownloadFolder() . '/' . $qs_action;

    //check if files exists, take latest released version (desc order files) 
    $latestInFolder = getLatestDownloadableFile($initialPath);
    if(!$latestInFolder) {
        $out();
        return;
    }

    //dest file
    $filepath = $initialPath . "/" . $latestInFolder;
    
    //apply headers
    ContextManager::get("header")('Content-Type: application/octet-stream');
    ContextManager::get("header")("Content-Transfer-Encoding: Binary"); 
    ContextManager::get("header")("Content-Length: ".filesize($filepath));
    ContextManager::get("header")('Content-Disposition: attachment; filename="'.$latestInFolder."\"");
    
    //return file
    ob_clean(); flush();
    readfile($filepath);
}

function getLatestDownloadableFile($searchFolder) {
    $result = array_diff(
        scandir($searchFolder, SCANDIR_SORT_DESCENDING),
        ['..', '.']
    );
    return count($result) ? $result[0] : null;
}

function getSubDirectoriesFromDirectory($searchFolder) {
    //
    $folder_exists = file_exists($searchFolder);
    if (!$folder_exists) {
        return [];
    }

    //must be a directory
    $is_eligible = function($filename) use ($searchFolder) {
        return is_dir($searchFolder . $filename);
    };
    
    return array_filter(
                array_diff(
                    scandir($searchFolder),
                    ['..', '.']
                ),
            $is_eligible);
}

function getFilesFromDirectory($searchFolder) {
    
    //must not be a directory nor starting with a dot (., .., ._DS_STORE, etc)
    $is_eligible = function($filename) use ($searchFolder) { 
        return !is_dir($searchFolder . $filename) && !startsWith($filename, ".");
    };

    return array_filter(
                scandir($searchFolder, SCANDIR_SORT_DESCENDING), 
            $is_eligible);

}

function availableDownloads() {
    $ret = [];
    $df = getCompanionAppDownloadFolder();

    $subdirs = getSubDirectoriesFromDirectory($df);
    
    foreach($subdirs as $subdir) {
        $foundFile = getLatestDownloadableFile($df . $subdir);
        if($foundFile) $ret[$subdir] = $foundFile;
    }

    return $ret;
}
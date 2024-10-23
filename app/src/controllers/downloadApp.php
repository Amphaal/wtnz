<?php 

function routerInterceptor_Download($request, $qs_action) {

    $out = function() {
        ob_end_clean(); 
        die;
    };

    //if xmlHttpRequest, cancel...
    if(isXMLHttpRequest($request)) $out();
    
    //target server folder
    $initialPath = constant("COMPANION_APP_DOWNLOADS_FOLDER") . $qs_action;

    //check if files exists, take latest released version (desc order files) 
    $latestInFolder = getLatestDownloadableFile($initialPath);
    if(!$latestInFolder) $out();

    //dest file
    $filepath = $initialPath . "/" . $latestInFolder;
    
    //apply headers
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-Length: ".filesize($filepath));
    header('Content-Disposition: attachment; filename="'.$latestInFolder."\"");
    
    //return file
    ob_clean(); flush();
    readfile($filepath);
}

function getLatestDownloadableFile($searchFolder) {
    $result = array_diff(
        scandir($searchFolder, SCANDIR_SORT_DESCENDING),
        array('..', '.')
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
                    array('..', '.')
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
    $ret = array();
    $df = constant("COMPANION_APP_DOWNLOADS_FOLDER");

    $subdirs = getSubDirectoriesFromDirectory($df);
    
    foreach($subdirs as $subdir) {
        $foundFile = getLatestDownloadableFile($df . $subdir);
        if($foundFile) $ret[$subdir] = $foundFile;
    }

    return $ret;
}
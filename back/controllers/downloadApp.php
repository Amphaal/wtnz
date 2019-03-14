<?php 

function routerDownload($action) {

    $out = function() {
        ob_end_clean(); 
        die;
    };

    //if xmlHttpRequest, cancel...
    if(isXMLHttpRequest()) $out();
    
    //target server folder
    $initialPath = $_SERVER["DOCUMENT_ROOT"].'/feedtnz/downloads/'.$action;

    //check if files exists, take latest released version (desc order files) 
    $latestInFolder = scandir($initialPath, SCANDIR_SORT_DESCENDING)[0];
    if($latestInFolder == "..") $out();

    //dest file
    $filepath = $initialPath . "/" . $latestInFolder;
    
    //apply headers
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header('Content-Disposition: attachment; filename="'.$latestInFolder.'"');
    
    //return file
    ob_clean(); flush();
    readfile($filepath);
}
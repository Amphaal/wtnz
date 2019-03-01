<?php 

function routerDownload($action) {
    $initialPath = $_SERVER["DOCUMENT_ROOT"].'/feedtnz/downloads/'.$action;

    $latestInFolder = scandir($initialPath, SCANDIR_SORT_DESCENDING)[0];
    $filepath = $initialPath . "/" . $latestInFolder;
    
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header('Content-Disposition: attachment; filename="'.$latestInFolder.'"');
    
    ob_clean(); flush();
    readfile($filepath);
}
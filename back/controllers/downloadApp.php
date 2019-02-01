<?php 

function routerDownload($action) {
    $initialPath = $_SERVER["DOCUMENT_ROOT"].'/feedtnz/downloads/'.$action;

    $latestInFolder = scandir($initialPath, SCANDIR_SORT_DESCENDING)[0];
    $filepath = $initialPath . "/" . $latestInFolder;

    header('Content-Type: '. $contentType);
    header('Content-Disposition: attachment; filename="'.$latestInFolder.'"');
    readfile($filepath);
}
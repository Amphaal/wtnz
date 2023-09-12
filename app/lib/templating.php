<?php

/** does an echo of each files contained in $path_to_dir folder (not recursive) */
function echoFilesOfFolder($path_to_dir) {
    foreach(getFilesInFolder($path_to_dir) as $file) { 
        echo file_get_contents($file);
    }
}

/** list alls files within a folder (not recursive) */
function getFilesInFolder($path_to) {
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));
    $ret = [];
    foreach($files as $file) { 
        array_push($ret, $path_to . "/" . $file);
    }
    return $ret;
}

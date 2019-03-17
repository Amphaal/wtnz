<?php 

function getFileUploadLimit(){
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}
 
//Render HTTP pattern from values
function renHpat($rules) {
    return ".{". $rules['min'] . "," . $rules['max'] . "}";
}

//POST remember
function PRem($post_val) {
    return isset($_POST[$post_val]) ? $_POST[$post_val] : "";
}

function getFilesInFolder($path_to) {
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));
    $ret = [];
    foreach($files as $file) { 
        array_push($ret, $path_to . '/' . $file);
    }
    return $ret;
}

function includeXMLRSwitch($inside_part, $included_vars_array) {

    foreach($included_vars_array as $varname => $value) {
        $$varname = $value;
    }

    unset($included_vars_array);
    unset($varname);
    unset($value);

    if(isXMLHttpRequest()) {
        include $inside_part;
    } else {
        include "back/ui/template/_template.php";
    }
    
}

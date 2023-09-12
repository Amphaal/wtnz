<?php 

/** generic cleaning of POST fields, if any */
function sanitizePOST() {
    if(array_key_exists('username', $_POST)) {
        $_POST['username'] = trim(strtolower($_POST['username']));
    }
} 

function getFileUploadLimit() {
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}
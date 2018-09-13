<?php 
$dynRoot =  __DIR__ . "/../../";
include_once $dynRoot . "back/helpers/helpers.php";

foreach(getFilesInFolder($dynRoot .'front/js/polyfills') as $file) { 
    echo file_get_contents($file);
}

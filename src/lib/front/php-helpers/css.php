<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/back/template/php-helpers/style.php";

    echo "<style>";

    foreach(getFilesInFolder('front/css') as $file) { 
        echo file_get_contents($file);
    } 

    echo "</style>";
?>

<?php
    echo "<style>";

    foreach(getFilesInFolder('back/template/css') as $file) { 
        echo file_get_contents($file);
    } 

    echo getCustomBackgroundAnimColors($user_qs);

    echo "</style>";
?>

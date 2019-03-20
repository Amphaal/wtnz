
<?php
    echo "<style>";

    foreach(getFilesInFolder('back/ui/template/css') as $file) { 
        echo file_get_contents($file);
    } 

    foreach(getFilesInFolder('front/css') as $file) { 
        echo file_get_contents($file);
    } 

    echo getCustomBackgroundAnimColors($user_qs);

    echo "</style>";
?>


<?php
    echo "<style>";

    include "back/ui/template/style.css";

    foreach(getFilesInFolder('front/css') as $file) { 
        echo file_get_contents($file);
    } 

    echo getCustomBackgroundAnimColors($user_qs);

    echo "</style>";
?>

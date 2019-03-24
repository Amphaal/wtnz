<?php
    echo "<style>";

    foreach(getFilesInFolder('back/template/css') as $file) { 
        echo file_get_contents($file);
    } 
    
    echo cbacToCss();

    echo "</style>";
?>

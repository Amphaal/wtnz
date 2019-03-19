<?php

    $css = "";

    foreach(getFilesInFolder('front/css') as $file) { 
        $css .= file_get_contents($file);
    } 

    $css .= getCustomBackgroundAnimColors($user_qs);

    echo "<style>" . $css . "</style>";
?>

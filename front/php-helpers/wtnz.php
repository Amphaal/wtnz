<?php include "back/template/php-helpers/js.php" ?>

<script>
    <?php
    
        foreach(getFilesInFolder('front/js/polyfills') as $file) { 
            echo file_get_contents($file);
        }

        foreach(getFilesInFolder('front/js/misc') as $file) { 
            echo file_get_contents($file);
        } 


        foreach(getFilesInFolder('front/js/wtnz') as $file) { 
            echo file_get_contents($file);
        } 

        foreach(getFilesInFolder('front/js/wtnz/panels') as $file) { 
            echo file_get_contents($file);
        } 

    ?>
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/js.php" ?>

<script>
    <?php
    
        foreach(getFilesInFolder('front/js/polyfills') as $file) { 
            echo file_get_contents($file);
        }

        foreach(getFilesInFolder('front/js/misc') as $file) { 
            echo file_get_contents($file);
        } 


        foreach(getFilesInFolder('public/js/app') as $file) { 
            echo file_get_contents($file);
        } 

        foreach(getFilesInFolder('public/js/app/panels') as $file) { 
            echo file_get_contents($file);
        } 

    ?>
</script>
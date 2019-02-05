<style>
<?php

foreach(getFilesInFolder('front/css') as $file) { 
    echo file_get_contents($file);
} 

?>
</style>
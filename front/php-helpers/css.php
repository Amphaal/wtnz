<style>
<?php

foreach(getFilesInFolder('front/css') as $file) { 
    echo file_get_contents($file);
} 

echo getCustomBackgroundAnimColors($user_qs);

?>
</style>
<script>
    <?php 
    $path_to = 'front/js/polyfills';
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));

    foreach($files as $jsfile) { 
        echo file_get_contents($path_to . '/' . $jsfile);
    }?>
</script>
<script>
    <?php

        foreach(getFilesInFolder('back/template/js') as $file) { 
            echo file_get_contents($file);
        }

    ?>
</script>
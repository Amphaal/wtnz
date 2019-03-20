<script>
    <?php

        foreach(getFilesInFolder('back/ui/template/js') as $file) { 
            echo file_get_contents($file);
        }

    ?>
</script>
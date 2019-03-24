<script>

    var i18n = <?php echo json_encode(I18nSingleton::getInstance()->getDictionary())?>;
    var lang = <?php echo json_encode(I18nSingleton::getInstance()->getLang())?>;

    <?php

        foreach(getFilesInFolder('back/template/js') as $file) { 
            echo file_get_contents($file);
        }

    ?>
</script>
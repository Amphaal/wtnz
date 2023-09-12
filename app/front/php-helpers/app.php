<?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/js.php" ?>
<script>
    <?php
        echoFilesOfFolder('front/js/polyfills');
        echoFilesOfFolder('front/js/misc');
        echoFilesOfFolder('front/js/app');
        echoFilesOfFolder('front/js/app/panels');
    ?>
</script>
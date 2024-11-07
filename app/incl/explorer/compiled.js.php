<?php 
    echoFilesOfFolder($sourcePhpRoot . "/public/ext/js/polyfills");

    include $sourcePhpRoot . "/layout/explorer/js/vars.php";
    echoFilesOfFolder($sourcePhpRoot . "/layout/explorer/js/misc");
    echoFilesOfFolder($sourcePhpRoot . "/layout/explorer/js/app");
    echoFilesOfFolder($sourcePhpRoot . "/layout/explorer/js/app/panels");
?>
<?php 
    echoFilesOfFolder(SOURCE_PHP_ROOT . "/public/ext/js/polyfills");

    include SOURCE_PHP_ROOT . "/layout/explorer/js/vars.php";
    echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/explorer/js/misc");
    echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/explorer/js/app");
    echoFilesOfFolder(SOURCE_PHP_ROOT . "/layout/explorer/js/app/panels");
?>
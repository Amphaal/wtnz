<?php 
    echoFilesOfFolder($documentRoot . "/public/ext/js/polyfills");

    include $documentRoot . "/layout/explorer/js/vars.php";
    echoFilesOfFolder($documentRoot . "/layout/explorer/js/misc");
    echoFilesOfFolder($documentRoot . "/layout/explorer/js/app");
    echoFilesOfFolder($documentRoot . "/layout/explorer/js/app/panels");
?>
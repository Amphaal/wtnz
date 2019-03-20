<!DOCTYPE html>
<html lang="<?php echo I18nSingleton::getInstance()->getLang()?>">
    <head>
        <?php include "back/ui/template/php-helpers/metadata.php" ?>
        <?php include "back/ui/template/php-helpers/libs.php" ?>
        <link rel="stylesheet" type="text/css" href="/wtnz/back/ui/template/css/style.css">
        <?php include "back/ui/template/php-helpers/js.php" ?>
        <script type="text/javascript" src="/wtnz/back/ui/template/app.js"></script>
    </head>
    <body>
        <?php include $inside_part; ?>
    </body>
</html>
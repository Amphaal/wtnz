<!DOCTYPE html>
<html lang="<?php echo I18nSingleton::getInstance()->getLang()?>">
    <head>
        <?php include "back/ui/template/metadata.php" ?>
        <link rel="stylesheet" type="text/css" href="back/ui/template/style.css">
    </head>
    <body>
        <?php include $inside_part; ?>
    </body>
</html>
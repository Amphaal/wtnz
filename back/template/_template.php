<!DOCTYPE html>
<html lang="<?php echo I18nSingleton::getInstance()->getLang()?>">
    <head>
        <?php include "back/template/php-helpers/metadata.php" ?>
        <?php include "back/template/php-helpers/libs.php" ?>
        <?php include "back/template/php-helpers/style.php" ?>
        <?php include "back/template/php-helpers/js.php" ?>
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include "back/template/_components/footer.php" ?>
    </body>
</html>
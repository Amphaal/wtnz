<!DOCTYPE html>
<html lang="<?php echo I18nSingleton::getInstance()->getLang();?>">
    <head>
        <title><?php echo $title;?></title>
        <link rel="icon" type="image/png" href="<?php echo $icon;?>" />
        <?php include "front/php-helpers/metadata.php" ?>
        <?php include "front/php-helpers/css.php" ?>
        <?php include "front/php-helpers/libs.php" ?>
        <?php include "front/php-helpers/vars.php" ?>
        <?php include "front/php-helpers/wtnz.php" ?>
    </head>
    <body>
        <?php include "front/ui/_components/bg.php" ?>
        <?php include "front/ui/_components/loader.php" ?>
        <?php include "front/ui/library/parts/shoutWidget.php" ?>
        <main>
            <?php include "front/ui/library/library.php" ?>
            <?php include "front/ui/account/account.php" ?>
        </main>
    </body>
</html>

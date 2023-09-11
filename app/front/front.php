<!DOCTYPE html>
<html lang="<?php echo I18nSingleton::getInstance()->getLang()?>">
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/metadata.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/php-helpers/css.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/php-helpers/libs.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/php-helpers/vars.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/php-helpers/app.php" ?>
        <?php 
            echo "<style>";

            echo cbacToCss($user_qs, UserDb::fromProtected($user_qs)["customColors"]);
        
            echo "</style>";
        ?>
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/ui/_components/loader.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/ui/library/parts/shoutWidget.php" ?>
        <main id="main-app">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/ui/library/library.php" ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/ui/account/account.php" ?>
        </main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/front/ui/_components/bg.php" ?>
    </body>
</html>

<!DOCTYPE html>
<html lang="<?= I18nSingleton::getInstance()->getLang() ?>">
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/metadata.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/libs.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/style.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/php-helpers/js.php" ?>
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include  $_SERVER['DOCUMENT_ROOT'] . "/app/back/template/_components/footer.php" ?>
    </body>
</html>
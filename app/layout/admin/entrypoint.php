<!DOCTYPE html>
<html lang="<?= I18nSingleton::getInstance()->getLang() ?>">
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/_any/metadata.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.js-ext.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.css.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.js.php" ?>
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include  $_SERVER['DOCUMENT_ROOT'] . "/app/layout/_any/footer.php" ?>
    </body>
</html>
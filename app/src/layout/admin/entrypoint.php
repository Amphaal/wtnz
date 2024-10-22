<!DOCTYPE html>
<html lang="<?= I18nSingleton::getInstance()->getLang() ?>">
    <head>
        <?php include $documentRoot . "/layout/_any/metadata.php" ?>
        <?php include $documentRoot . "/layout/admin/compiled.js-ext.php" ?>
        <?php include $documentRoot . "/layout/admin/compiled.css.php" ?>
        <?php include $documentRoot . "/layout/admin/compiled.js.php" ?>
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include  $documentRoot . "/layout/_any/footer.php" ?>
    </body>
</html>
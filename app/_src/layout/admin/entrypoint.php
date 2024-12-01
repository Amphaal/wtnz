<!DOCTYPE html>
<html lang="<?= I18nHandler::get()->getLang() ?>">
    <head>
        <?php include SOURCE_PHP_ROOT . "/layout/_any/metadata.php" ?>
        <?php include SOURCE_PHP_ROOT . "/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="<?= getPublicPhpWebRoot() ?>/admin/compiled.js.php"></script>
        <link rel="stylesheet" href="<?= getPublicPhpWebRoot() ?>/admin/compiled.css.php">
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include SOURCE_PHP_ROOT . "/layout/_any/footer.php" ?>
    </body>
</html>
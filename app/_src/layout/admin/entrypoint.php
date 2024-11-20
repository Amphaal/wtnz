<!DOCTYPE html>
<html lang="<?= ContextManager::get("i18nS")->getLang() ?>">
    <head>
        <?php include $sourcePhpRoot . "/layout/_any/metadata.php" ?>
        <?php include $sourcePhpRoot . "/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="/public_php/admin/compiled.js.php"></script>
        <link rel="stylesheet" href="/public_php/admin/compiled.css.php">
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include  $sourcePhpRoot . "/layout/_any/footer.php" ?>
    </body>
</html>
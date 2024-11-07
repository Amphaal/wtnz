<!DOCTYPE html>
<html lang="<?= ContextManager::get("i18nS")->getLang() ?>">
    <head>
        <?php include $documentRoot . "/layout/_any/metadata.php" ?>
        <?php include $documentRoot . "/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="/incl/admin/compiled.js.php"></script>
        <link rel="stylesheet" href="/incl/admin/compiled.css.php">
    </head>
    <body>
        <div id="mainFrame">
            <?php include $inside_part; ?>
        </div>
        <?php include  $documentRoot . "/layout/_any/footer.php" ?>
    </body>
</html>
<!DOCTYPE html>
<html lang="<?= I18nHandler::get()->getLang() ?>">
    <head>
        <?php include SOURCE_PHP_ROOT . "/layout/_any/metadata.php" ?>

        <?php /** 1a. external JS libs */ ?>
        <?php include SOURCE_PHP_ROOT . "/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="<?= getPublicWebRoot() ?>/ext/js/highcharts.js"></script>
        <script type="text/javascript" src="<?= getPublicWebRoot() ?>/ext/js/moment-with-locales.min.js"></script>
        <script type="text/javascript" src="<?= getPublicWebRoot() ?>/ext/js/sorttable.js"></script>
        <script type="text/javascript" src="<?= getPublicWebRoot() ?>/ext/js/hammer.min.js"></script>
        <script type="text/javascript" src="<?= getPublicWebRoot() ?>/ext/js/mixitup.min.js"></script>
        <?php /** 1b. internal JS libs + PHP-to-JS variables */ ?>
        <script type="text/javascript" src="<?= getPublicPhpWebRoot() ?>/admin/compiled.js.php"></script>
        <script type="text/javascript" src="<?= getPublicPhpWebRoot() ?>/explorer/compiled.js.php"></script>

        <?php /** 2aa All-purposes CSS */ ?>
        <link rel="stylesheet" href="<?= getPublicPhpWebRoot() ?>/admin/compiled.css.php">
        <link rel="stylesheet" href="<?= getPublicPhpWebRoot() ?>/explorer/compiled.css.php">

    </head>
    <body>
        <?php include SOURCE_PHP_ROOT . "/layout/explorer/components/loader.php" ?>
        <?php include SOURCE_PHP_ROOT . "/layout/explorer/components/music_library/parts/shoutWidget.php" ?>
        <main id="main-app">
            <?php include SOURCE_PHP_ROOT . "/layout/explorer/components/music_library/music_library.php" ?>
            <?php include SOURCE_PHP_ROOT . "/layout/explorer/components/account.php" ?>
        </main>
        <?php include SOURCE_PHP_ROOT . "/layout/explorer/components/bg.php" ?>
    </body>
</html>

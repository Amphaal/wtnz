<!DOCTYPE html>
<html lang="<?= ContextManager::get("i18nS")->getLang() ?>">
    <head>
        <?php include $documentRoot . "/layout/_any/metadata.php" ?>

        <?php /** 1a. external JS libs */ ?>
        <?php include $documentRoot . "/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="/public/ext/js/highcharts.js"></script>
        <script type="text/javascript" src="/public/ext/js/moment-with-locales.min.js"></script>
        <script type="text/javascript" src="/public/ext/js/sorttable.js"></script>
        <script type="text/javascript" src="/public/ext/js/hammer.min.js"></script>
        <script type="text/javascript" src="/public/ext/js/mixitup.min.js"></script>
        <?php /** 1b. internal JS libs + PHP-to-JS variables */ ?>
        <script type="text/javascript" src="/incl/admin/compiled.js.php"></script>
        <script type="text/javascript" src="/incl/explorer/compiled.js.php"></script>

        <?php /** 2aa All-purposes CSS */ ?>
        <link rel="stylesheet" href="/incl/admin/compiled.css.php">
        <link rel="stylesheet" href="/incl/explorer/compiled.css.php">

    </head>
    <body>
        <?php include $documentRoot . "/layout/explorer/components/loader.php" ?>
        <?php include $documentRoot . "/layout/explorer/components/music_library/parts/shoutWidget.php" ?>
        <main id="main-app">
            <?php include $documentRoot . "/layout/explorer/components/music_library/music_library.php" ?>
            <?php include $documentRoot . "/layout/explorer/components/account.php" ?>
        </main>
        <?php include $documentRoot . "/layout/explorer/components/bg.php" ?>
    </body>
</html>

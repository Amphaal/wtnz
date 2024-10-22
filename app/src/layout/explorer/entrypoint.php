<!DOCTYPE html>
<html lang="<?= I18nSingleton::getInstance()->getLang() ?>">
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
        <?php include $documentRoot . "/layout/explorer/js/vars.php" ?>
        <?php include $documentRoot . "/layout/admin/compiled.js.php" ?>
        <script>
            <?php
                echoFilesOfFolder($documentRoot . "/public/ext/js/polyfills");
                echoFilesOfFolder($documentRoot . "/layout/explorer/js/misc");
                echoFilesOfFolder($documentRoot . "/layout/explorer/js/app");
                echoFilesOfFolder($documentRoot . "/layout/explorer/js/app/panels");
            ?>
        </script>

        <?php /** 2aa All-purposes CSS */ ?>
        <?php include $documentRoot . "/layout/admin/compiled.css.php"; ?>
        <style>
            <?php echoFilesOfFolder($documentRoot . "/layout/explorer/css"); ?>
        </style>
        <?php /** 2b. Profile specific CSS */ ?>
        <style>
            <?= cbacToCss($qs_user, UserDb::fromProtected($qs_user)["customColors"]) ?>
        </style>
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

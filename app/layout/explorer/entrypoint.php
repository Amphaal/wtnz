<!DOCTYPE html>
<html lang="<?= I18nSingleton::getInstance()->getLang() ?>">
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/_any/metadata.php" ?>

        <?php /** 1a. external JS libs */ ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.js-ext.php" ?>
        <script type="text/javascript" src="/public/ext/js/highcharts.js"></script>
        <script type="text/javascript" src="/public/ext/js/moment-with-locales.min.js"></script>
        <script type="text/javascript" src="/public/ext/js/sorttable.js"></script>
        <script type="text/javascript" src="/public/ext/js/hammer.min.js"></script>
        <script type="text/javascript" src="/public/ext/js/mixitup.min.js"></script>
        <?php /** 1b. internal JS libs + PHP-to-JS variables */ ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/js/vars.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.js.php" ?>
        <script>
            <?php
                echoFilesOfFolder($_SERVER['DOCUMENT_ROOT'] . '/app/public/ext/js/polyfills');
                echoFilesOfFolder($_SERVER['DOCUMENT_ROOT'] . '/app/layout/explorer/js/misc');
                echoFilesOfFolder($_SERVER['DOCUMENT_ROOT'] . '/app/layout/explorer/js/app');
                echoFilesOfFolder($_SERVER['DOCUMENT_ROOT'] . '/app/layout/explorer/js/app/panels');
            ?>
        </script>

        <?php /** 2aa All-purposes CSS */ ?>
        <style>
            <?php
                include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/compiled.css.php";
                echoFilesOfFolder($_SERVER['DOCUMENT_ROOT'] . '/app/layout/explorer/css');
            ?>
        </style>
        <?php /** 2b. Profile specific CSS */ ?>
        <style>
            <?= cbacToCss($user_qs, UserDb::fromProtected($user_qs)["customColors"]) ?>
        </style>
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/loader.php" ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/music_library/parts/shoutWidget.php" ?>
        <main id="main-app">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/music_library/music_library.php" ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/account.php" ?>
        </main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/bg.php" ?>
    </body>
</html>

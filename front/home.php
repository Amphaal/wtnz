<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>WTNZ - <?php echo $user_qs ?>'s Library</title>
        <link rel="stylesheet" href="front/css/lib/animate.css">
        <link rel="stylesheet" href="front/css/style.css">
        <link rel="stylesheet" href="front/css/stats.css">
        <link rel="stylesheet" href="front/css/filter.css">
        <link rel="stylesheet" href="front/css/searchBand.css">
        <link rel="stylesheet" href="front/css/albumInfos.css">
        <link rel="icon" type="image/png" href="front/img/ico.png" />
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://momentjs.com/downloads/moment.min.js"></script>
        <?php include "front/js-php/vars.php" ?>
        <?php include "front/js-php/polyfills.php" ?>
        <script src="front/js/sub/helpers.js"></script>
        <script src="front/js/sub/ui.js"></script>
        <script src="front/js/sub/data.js"></script>
        <script src="front/js/sub/stats.js"></script>
        <script src="front/js/core.js"></script>
    </head>
    <body>
        <?php include "front/ui/loader.php" ?>
        <div id='wtnz'>
            <?php include "front/ui/head.php" ?>
            <div id='mainFrame'>
                <?php include "front/ui/stats.php" ?>
                <div id='content'>
                    <?php include "front/ui/feed.php" ?>
                    <?php include "front/ui/discover.php" ?>
                </div>
            </div>
            <?php include "front/ui/footer.php" ?>
        </div>
    </body>
</html>

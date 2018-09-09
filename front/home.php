<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>WTNZ - <?php echo $user_qs ?>'s Library</title>
        <link rel="stylesheet" href="front/css/lib/animate.css">
        <link rel="stylesheet" href="front/css/stats.css">
        <link rel="stylesheet" href="front/css/filter.css">
        <link rel="stylesheet" href="front/css/style.css">
        <link rel="icon" type="image/png" href="front/img/ico.png" />
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <?php include "front/js/vars.php" ?>
        <script src="front/js/helpers.js"></script>
        <script src="front/js/ui.js"></script>
        <script src="front/js/data.js"></script>
        <script src="front/js/stats.js"></script>
        <script src="front/js/core.js"></script>
    </head>
    <body>
        <?php include "front/ui/loader.php" ?>
        <div id='content'>
            <?php include "front/ui/head.php" ?>
            <div id='mainFrame'>
                <?php include "front/ui/stats.php" ?>
                <?php include "front/ui/feed.php" ?>
                <?php include "front/ui/discover.php" ?>
            </div>
        </div>
    </body>
    <a id='footer' href='https://www.linkedin.com/in/guillaumevara/' target="_blank">
        Pre-Alpha - 2018 - GV
    </a>
</html>

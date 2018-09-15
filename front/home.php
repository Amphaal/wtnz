<?php 
    $title = "WTNZ - " . $user_qs . "'s Library";
    $icon = "front/img/ico.png";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title;?></title>
        <link rel="icon" type="image/png" href="<?php echo $icon;?>" />
        <?php include "front/php-helpers/metadata.php" ?>
        <?php include "front/php-helpers/css.php" ?>
        <?php include "front/php-helpers/libs.php" ?>
        <?php include "front/php-helpers/vars.php" ?>
        <?php include "front/php-helpers/wtnz.php" ?>
    </head>
    <body>
        <?php include "front/ui/loader.php" ?>
        <main id='wtnz'>
            <?php include "front/ui/header.php" ?>
            <div id='mainFrame'>
                <?php include "front/ui/stats.php" ?>
                <?php include "front/ui/feed.php" ?>
                <?php include "front/ui/discover.php" ?>
            </div>
            <?php include "front/ui/footer.php" ?>
        </main>
    </body>
</html>

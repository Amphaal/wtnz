<header>
    <div id='menu'>
        <div class='fctr' id='logo'>
            <span>WTNZ</span>
        </div>
        <div id='banner'>
            <div id='banner-desc'>
                <?php echo i18n('libraryOfHead', $user_qs)?>
            </div>
            <?php include 'front/ui/library/parts/menu.php' ?>
            <?php include 'front/ui/library/parts/searchBand.php' ?>
        </div>
        <div class='connect-side'>
            <?php include 'front/ui/_components/connect_btn.php' ?>
        </div>
    </div>
    <?php include "front/ui/library/parts/profile.php" ?>
    <div class='anim'></div>
</header>
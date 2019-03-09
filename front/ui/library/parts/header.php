<header>
    <div id='menu'>
        <span class='fctr logo'></span>
        <div id='banner'>
            <div id='banner-desc'>
                <?php echo i18n('libraryOfHead', $user_qs)?>
            </div>
            <?php include 'front/ui/library/parts/menu.php' ?>
            <?php include 'front/ui/library/parts/searchBand.php' ?>
        </div>
        <?php include 'front/ui/_components/connect_btn.php' ?>
    </div>
    <?php include "front/ui/library/parts/profile.php" ?>
    <div class='wAnim'></div>
</header>
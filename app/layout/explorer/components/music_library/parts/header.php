<header>
    <div id='menu'>
        <span class='fctr logo'></span>
        <div id='banner'>
            <div id='banner-desc'>
                <?php echo i18n('libraryOfHead', $user_qs)?>
            </div>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/music_library/parts/menu.php" ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/music_library/parts/searchBand.php" ?>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/connect_btn.php" ?>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/components/music_library/parts/profile.php" ?>
    <?php echo _wAnim($user_qs)?>
</header>
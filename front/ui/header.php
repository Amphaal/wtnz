<header>
    <div class='fctr' id='logo'>
        <span>WTNZ</span>
    </div>
    <div id='banner'>
        <div id='banner-desc'>
            <?php echo i18n('libraryOfHead', $user_qs)?>
        </div>
        <?php include 'front/ui/searchBand.php' ?>
        <div id='banner-side'>
            <label title="<?php echo i18n("feed")?>">
                <input id='showFeed' type='checkbox' onclick="toggleFeed(event)" autocomplete="off">
                <i class="far fa-newspaper"></i>
            </label>
            <label title="<?php echo i18n("stats")?>">
                <input id='showStats' type='checkbox' onclick="toggleStats(event)" autocomplete="off">
                <i class="fas fa-chart-pie"></i>
            </label>   
        </div>
        <div class='anim'></div>
    </div>
</header>
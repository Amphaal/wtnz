<div id='discoverContainer'>
    <div class='subFrame'>
        <div class='subContent' data-cat='<?php echo i18n('discover')?>' style="margin-bottom:1rem;flex-wrap:wrap;">
            <div class='filterWrapper'>
                <div data-sl='Genres' id='genreUI'></div>
                <div data-sl='<?php echo i18n("artists")?>' id='artistUI'></div>
                <div data-sl='Albums' id='albumUI'></div>
            </div>
        </div>
        <?php include 'front/ui/albumInfos.php' ?>
    </div>
</div>
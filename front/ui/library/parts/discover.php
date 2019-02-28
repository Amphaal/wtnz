<div id='discoverContainer' class='subFrame'>
    <div class='subContent' data-cat='<?php echo i18n('discover')?>'>
        <?php include 'front/ui/library/parts/sorter.php' ?>
        <div class='filterWrapper'>
            <div data-sl='Genres' id='genreUI'></div>
            <div data-sl='<?php echo i18n("artists")?>' id='artistUI'></div>
            <div data-sl='Albums' id='albumUI'></div>
        </div>
        <?php include 'front/ui/library/parts/albumInfos.php' ?>
    </div>
</div>
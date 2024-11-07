<div id='discoverContainer' class='subFrame'>
    <div class='subContent' data-cat='<?= ContextManager::get("i18n")('discover')?>'>
        <div class="sorter"></div>
        <div class='filterWrapper'>
            <div data-sl='Genres' id='genreUI'></div>
            <div data-sl='<?= ContextManager::get("i18n")("artists")?>' id='artistUI'></div>
            <div data-sl='Albums' id='albumUI'></div>
        </div>
        <?php include $documentRoot . "/layout/explorer/components/music_library/parts/albumInfos.php" ?>
    </div>
</div>
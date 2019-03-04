<div id='statsContainer' class='subFrame'>
    <div class='subContent' data-cat="STATS">
        <div class='statsWrapper'>
            <div id='stats'>
                <div class='statsPh' id='statsAlbums' data-phid='0' data-def="Albums"></div>
                <div class='statsPh' id='statsArtists' data-phid='1' data-def="<?php echo i18n("artists") ?>"></div>
            </div>
            <div id='statsSwitcher'>
                <div>
                    <label class="clickable" title="Albums">
                        <input id='rAlbums' data-phid='0' type='radio' name='statsS' onchange="switchPanel(event)" checked autocomplete="off">
                        <i class="fas fa-compact-disc"></i>
                        <span>Albums</span>
                    </label>
                </div>
                <div>
                    <label class="clickable" title='<?php echo i18n("artists") ?>'>
                        <input id='rArtists' data-phid='1' type='radio' name='statsS' onchange="switchPanel(event)" autocomplete="off">
                        <i class="fas fa-users"></i>
                        <span><?php echo i18n("artists") ?></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
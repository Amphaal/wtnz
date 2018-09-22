<div id='statsContainer' style="height:0">
    <div class='subFrame'>
        <div class='subContent' data-cat="STATS" style="flex:1">
            <div class='statsWrapper'>
                <div id='stats'>
                    <div class='statsPh' id='statsAlbums' data-phid='0'></div>
                    <div class='statsPh' id='statsArtists' data-phid='1'></div>
                </div>
                <div id='statsSwitcher'>
                    <div>
                        <label title="Albums">
                            <input id='rAlbums' data-phid='0' type='radio' name='statsS' onclick="switchPanel(event)" checked autocomplete="off">
                            <i class="fas fa-compact-disc"></i>
                            <span>Albums</span>
                        </label>
                    </div>
                    <div>
                        <label title='Artists'>
                            <input id='rArtists' data-phid='1' type='radio' name='statsS' onclick="switchPanel(event)" autocomplete="off">
                            <i class="fas fa-users"></i>
                            <span>Artists</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
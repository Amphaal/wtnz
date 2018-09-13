<div id='statsContainer' style="height:0" data-cat="STATS">
    <div id='stats'>
        <div class='statsPh' id='statsAlbums' data-phid='0'></div>
        <div class='statsPh' id='statsArtists' data-phid='1'></div>
    </div>
    <div id='statsSwitcher'>
        <div>
            <input id='rAlbums' data-phid='0' type='radio' name='statsS' onclick="switchPanel(event)" checked>
            <label for="rAlbums">Albums</label>
        </div>
        <div>
            <input id='rArtists' data-phid='1' type='radio' name='statsS' onclick="switchPanel(event)">
            <label for="rArtists">Artists</label>
        </div>
    </div>
</div>
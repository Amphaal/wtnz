<div id='albumInfos'>
    <div class='aiContent'>
        <div class='hInfos'>
                <label>Year<div id="aYear"></div></label>
                <label>Genre<div id="aGenre"></div></label>
                <label>Date of addition<div id="aDateAdded"></div></label>
        </div>
        <div class='hMisc'>
            <div class='imgContainer'>
                <label>Album<div id="aTitle"></div></label>
                <div id='aImage' class='imgLoader'><img onload="imgLoaded(event)" onerror="brokenImg(event)" /></div>
            </div>
            <label style='margin: 1rem 0 1rem 2rem' >Tracks<ol id="aTracks"></ol></label>
        </div>
        <div class='listen'>
            <a target='_blank' class='animated'>
                <i class="fab fa-youtube"></i>
                <span>Listen to the album</span>
            </a>
        </div>
    </div>
    <div class='anim'></div>
</div>
<div id='albumInfos'>
    <div class='aiContent'>
        <div class='hInfos'>
                <label><?php echo i18n("year")?><div id="aYear"></div></label>
                <label>Genre<div id="aGenre"></div></label>
                <label><?php echo i18n("dateAddition")?><div id="aDateAdded"></div></label>
        </div>
        <div class='hMisc'>
            <div class='imgContainer'>
                <label>Album<div id="aTitle"></div></label>
                <div id='aImage' class='imgLoader' data-no-cover-found="<?php echo i18n("no_cover_found")?>"><img onload="imgLoaded(event)" onerror="brokenImg(event)" alt="" /></div>
            </div>
            <label style='margin: 1rem 0 1rem 2rem' ><?php echo i18n("tracks")?><ol id="aTracks"></ol></label>
        </div>
        <div class='listen'>
            <a class='animated' rel="noopener">
                <i class="fab fa-youtube"></i>
                <span><?php echo i18n("listenTTA")?></span>
            </a>
        </div>
    </div>
    <div class='anim'></div>
</div>
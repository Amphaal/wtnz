<div class='subFrame'>
    <div class='subContent' data-cat='DISCOVER' style="margin-bottom:1rem;flex-wrap:wrap">
        <div data-sl='Genres' id='genreUI'></div>
        <div data-sl='Artists' id='artistUI'></div>
        <div data-sl='Albums' id='albumUI'></div>
    </div>
    <div id='albumInfos' class='anim'>
        <div class='aiContent'>
            <div class='hInfos'>
                <div>
                    <label for="aYear">Year</label>
                    <div id="aYear"></div>
                </div>
                <div>
                    <label for="aGenre">Genre</label>
                    <div id="aGenre"></div>
                </div>
                <div>
                    <label for="aDateAdded">Date of addition</label>
                    <div id="aDateAdded"></div>
                </div>
            </div>
            <div class='hMisc'>
                <div class='imgContainer'>
                    <div>   
                        <label for="aTitle">Album</label>
                        <div id="aTitle"></div>
                    </div>
                    <div id='aImage'><img onerror="brokenImg(event)" /></div>
                </div>
                <div>
                    <label for="aTracks">Tracks</label>
                    <ol id="aTracks"></ol>
                </div>
            </div>
        </div>
    </div>
</div>
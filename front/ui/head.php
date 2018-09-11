<div id='head'>
    <div class='fctr' id='logo'>
        <span>WTNZ</span>
    </div>
    <div id='banner' class='anim'>
        <div id='banner-desc'>
            <span style='color : white'><?php echo $user_qs ?></span><span style='color : #313030'> Library</span>
        </div>
        <div id='searchBand'>
            <div class='search' >
                <input spellcheck="false" type='text' placeholder="Search a band name..." 
                onkeyup="searchBand(event)" onfocus="toggleSearchResults(event)" onblur="toggleSearchResults(event)">
                <div class='searchResults'></div>
            </div>
        </div>
        <div id='banner-side'>

            <div>
                <label for='showStats'>Statistics</label>
                <input id='showStats' type='checkbox' onclick="toggleStats(event)" style='margin:0;margin-left:0.25rem;'>
            </div>
        </div>
    </div>
</div>
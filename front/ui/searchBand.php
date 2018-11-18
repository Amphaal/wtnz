<div id='searchBand'>
    <div class='search' data-found="<?php echo i18n("found")?>">
        <input spellcheck="false" type='text' placeholder="<?php echo i18n("phSearch")?>" 
        onkeyup="searchBand(event)" onfocus="toggleSearchResults(event)" onblur="toggleSearchResults(event)" onkeydown="handleKeysSearchBand(event)" autocomplete="off" >
        <div class='searchResults'></div>
    </div>
</div>
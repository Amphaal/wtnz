//download library
function requestUserLib() {
    var request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {return processLibAsJSON(e.target.responseText)};
    request.open('GET', clientURLLibrary, true);
    request.send(null);
}

function renderStats(albumsByGenre, artistsByGenre) {
    renderHCPie(descSortObj(albumsByGenre), 'statsAlbums', 'Albums');
    renderHCPie(descSortObj(artistsByGenre), 'statsArtists', 'Artists');
}

//process...
albumsByArtists = null;
artistsByGenre = null;
function processLibAsJSON(JSONText) {
    //parse
    var lib = JSON.parse(JSONText);

    //bind data
    albumsByArtists = albumsByArtistsList(lib);
    artistsByGenre = artistsByGenreList(lib);
    albumsByGenre = albumsByGenreCount(lib);
    renderStats(albumsByGenre, artistsByGenre);

    //bind UI elements to document
    filterByGenreUI = generateFilterByGenreUI(albumsByGenre);
    document.getElementById('content').appendChild(filterByGenreUI);

    //animations...
    hideLoader();
    showContent();
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

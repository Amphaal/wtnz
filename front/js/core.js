//download library
function requestUserLib() {
    var request = new XMLHttpRequest(); 
    request.onprogress=updateProgress;
    request.onloadend = e => processLibAsJSON(e.target.responseText);
    request.open('GET', clientURLLibrary, true);
    request.send(null);
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

    //bind UI elements to document
    filterByGenreUI = generateFilterByGenreUI(artistsByGenre);
    document.getElementById('m-list').appendChild(filterByGenreUI);

    //animations...
    hideLoader();
    showContent();
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});


  
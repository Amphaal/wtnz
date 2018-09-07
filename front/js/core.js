'use strict';

//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
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
function processLibAsJSON(JSONText) {
    //parse
    let lib = JSON.parse(JSONText);

    //bind data
    let albumsByArtists = albumsByArtistsList(lib);
    let artistsByGenre = artistsByGenreList(lib);
    let albumsByGenre = albumsByGenreCount(lib);
    let albumsList = Object.keys(albumsByArtists).reduce(function(total,current) {
        let albums = Object.keys(albumsByArtists[current]['Albums']);
        Array.prototype.push.apply(total,albums);
        return total;
    }, []); 
    
    //stats rendering
    renderStats(albumsByGenre, artistsByGenre);

    //bind UI elements to document
    let filterByGenreUI = generateFilterByGenreUI(albumsByGenre);
    document.getElementById('sub-content').appendChild(filterByGenreUI);

    let filterByArtistsUI = generateFilterByArtistsUI(albumsByArtists);
    document.getElementById('sub-content').appendChild(filterByArtistsUI);

    let albumsFilteredUI = generateAlbumsFilteredUI(albumsList);
    document.getElementById('sub-content').appendChild(albumsFilteredUI);
    
    //animations...
    hideLoader();
    showContent();
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

'use strict';

//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {return processLibAsJSON(e.target.responseText)};
    request.open('GET', clientURLLibrary, true);
    request.send(null);
}

var filter = {
    genreUI : null,
    artistUI : null,
    albumUI : null
};

var dataFeed = {
    genreUI : null, 
    artistUI : null, 
    albumUI : null
};

function applyFilter(toGenerate) {

    let toAlter = Object.keys(dataFeed);
    if(toGenerate == null) toGenerate = toAlter;

    toGenerate.forEach(function(id) {
        generateUI(id, dataFeed[id]);
    });

    toAlter.forEach(function(id) {
        alterUI(id, filter[id]);
    });

}

function updateFilter(event) {
    //update filter
    let nodeFilter = event.currentTarget.dataset.nFilter;
    let filterCat = event.currentTarget.parentNode.parentNode.id;
    nodeFilter == filter[filterCat] ? filter[filterCat] = null : filter[filterCat] = nodeFilter;
    
    //clean filters after currently set
    let uiFiltersToReset = Object.keys(filter);
    let indexCurrentFilter = uiFiltersToReset.indexOf(filterCat) + 1;
    uiFiltersToReset = uiFiltersToReset.slice(indexCurrentFilter);
    uiFiltersToReset.forEach(function(e) {filter[e] = null;});

    //apply filters
    applyFilter(uiFiltersToReset);
}

//process...
function processLibAsJSON(JSONText) {
    //parse
    let lib = JSON.parse(JSONText);

    //stats rendering
    renderStats(lib);

    //data feeding functions binding
    dataFeed.genreUI = function() {
        return albumsByGenreCount(lib);
    };
    dataFeed.artistUI = function() {
        let filterCriteria = filter['genreUI'];
        if(!filterCriteria) return;

        let artistsOfGenre = artistsByGenreList(lib)[filterCriteria];
        let apa = albumsByArtistsList(lib);
        return Array.from(artistsOfGenre).reduce(function(result, current) {
            result[current] = Object.keys(apa[current]['Albums']).length;
            return result;
        }, {});
    }

    //prepare UI
    Object.keys(dataFeed).forEach(function(id) {
        prepareUIPart(id);
    });

    //instantiate UI
    applyFilter();

    //end loading, start animations...
    hideLoader();
    showContent();
}



//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

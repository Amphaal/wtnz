//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {return processLibAsJSON(e.target.responseText)};
    request.open('GET', clientURLLibrary, true);
    request.send(null);
}

//global
var filter = {
    genreUI : null,
    artistUI : null,
    albumUI : null
};

var dataFeed = {
    genreUI : null, 
    artistUI : null, 
    albumUI : null,
    albumInfos : null
};

//data feeding functions binding
function bindDataFeeds(lib) {
        
    //genreUI
    dataFeed.genreUI = function() {
        return albumsByGenreCount(lib);
    };

    //artistUI
    dataFeed.artistUI = function() {
        let filterCriteria = filter['genreUI'];
        if(!filterCriteria) return;

        let artistsOfGenre = artistsByGenreList(lib)[filterCriteria];
        let apa = albumsByArtistsList(lib);
        
        //artists array
        let arrAog = [];
        artistsOfGenre.forEach(function(val) {arrAog.push(val);});
        
        return arrAog.reduce(function(result, current) {
            result[current] = Object.keys(apa[current]['Albums']).length;
            return result;
        }, {});
    };

    //albumUI
    dataFeed.albumUI = function() {
        let filterCriteria = filter['artistUI'];
        if(!filterCriteria) return;

        let apa = albumsByArtistsList(lib);
        let source = apa[filterCriteria]['Albums'];
        
        return Object.keys(source).reduce(function(result, current) {
            result[current] = source[current]['Year'];
            return result;
        }, {});
    };

    //albumInfos
    dataFeed.albumInfos = function() {
        let fArtist = filter['artistUI'];
        let fAlbum = filter['albumUI'];
        if(!fArtist || !fAlbum) return;

        return albumsByArtistsList(lib)[fArtist]['Albums'][fAlbum];
    }

    dataFeed.searchBand = function(filterCriteria) {
        if(!filterCriteria) return;

        filterCriteria = filterCriteria.toLowerCase();
        fCritLen = filterCriteria.length;

        let source = albumsByArtistsList(lib);
        let results = Object.keys(source).reduce(function(total, current) {

            let sIndex = current.toLowerCase().indexOf(filterCriteria);
            let sIndexEnd = fCritLen + sIndex;
            if(sIndex > -1)  {
                total[current] = {
                    Genres :  source[current]["Genres"],
                    sIndexRange : [sIndex, sIndexEnd]
                };
            }

            return total;
        }, {});

        return Object.keys(results).length ? results : null;
    }
}

function searchBand(event) {
    let criteria = event.currentTarget.value;
    let data = dataFeed.searchBand(criteria);
    renderSearchResults(criteria, data);
}

//apply filtering and generate / alter UI
function applyFilter(toGenerate) {

    let toAlter = Object.keys(filter);
    if(toGenerate == null) toGenerate = toAlter;

    toGenerate.forEach(function(id) {
        generateFilterUI(id, dataFeed[id]);
    });

    toAlter.forEach(function(id) {
        alterFilterUI(id, filter[id]);
    });

    displayAlbumInfos(dataFeed.albumInfos);
}

//update filtering regarding clicked element
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

    //bind
    bindDataFeeds(lib);

    //prepare UI
    Object.keys(filter).forEach(function(id) {
        prepareFilterUI(id);
    });

    //instantiate UI
    applyFilter();

    //end loading, start animations...
    hideLoader().then(function() {
        showApp().then(function() {
            //remove loader
            let target = document.getElementById("loader-container");
            target.parentElement.removeChild(target);
        });
    });
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

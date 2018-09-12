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
        
        //reduce
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

        let obj = albumsByArtistsList(lib)[fArtist]['Albums'][fAlbum];
        obj['Album'] = fAlbum;
        return obj;
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

//search band through head input
function searchBand(event) {
    let criteria = event.currentTarget.value;
    let data = dataFeed.searchBand(criteria);
    renderSearchResults(criteria, data);
}

//update filtering regarding clicked element
function updateFilter(event) {
    
    //prepare
    let dataFilters = event.currentTarget.dataset.nFilter;
    let filterUIBound = event.currentTarget.parentNode.parentNode;

    //handle single arg with primitive data, essentially from button click
    if (!IsJsonString(dataFilters) && filterUIBound.classList.contains('filterUI')) {
        
        //preapre
        let dFilter = dataFilters;
        let filterCat = filterUIBound.id;
        purgeFilterUntilEnd = true; // we purge remaining filters all the way to the right (index-wise)
        
        //if dest filter is equal to current, it means that the user wants to remove it
        if(dFilter && filter[filterCat] == dFilter) dFilter = null;

        //update dataFilters obj
        dataFilters = {};
        dataFilters[filterCat] = dFilter;

    } else {
        //parse filter string to Obj
        dataFilters = JSON.parse(dataFilters);
    }

    let toUpdate = alterFilter(dataFilters);
 
    //apply filters
    applyFilter(toUpdate);
}

function alterFilter(dataFilters) {

    let updatedIDs = [];
    let allFiltersArr = Object.keys(filter);
    let filtersArr = Object.keys(dataFilters);

    //define method to use by number of filters applied
    if (filtersArr.length > 1) {

        //search-like
        allFiltersArr.forEach(function(id) {
            if(filter[id] != dataFilters[id]) updatedIDs.push(id);
            filter[id] = dataFilters[id] || null;
        });

    } else if (dataFilters[filtersArr[0]] == null) {
        filter[filtersArr[0]] = null;
        let cc = allFiltersArr.indexOf(filtersArr[0]);
        let vv = allFiltersArr[cc + 1];
        return vv ?  [vv] : [];

    } else {
        //nav-like
        let resetFilter = 0;
        allFiltersArr.forEach(function(id) {
            let w = dataFilters[id];
            
            if (typeof w === 'undefined' && ! resetFilter) {
                if (resetFilter) w = null;
                else return;
            }

            //next filters will be reset
            if(filter[id] != w) resetFilter = 1;

            //set
            filter[id] = w;
            updatedIDs.push(id);
        });
    }

    let firstChainedFilterIndex = allFiltersArr.indexOf(updatedIDs.shift());
    let toRegenerate = allFiltersArr.filter(function(val, index){
        let previousFilter = allFiltersArr[index-1];
        let previousFilterValue = previousFilter ? filter[previousFilter] : null;
        return index > firstChainedFilterIndex;
    });

    return toRegenerate;
}


//apply filtering and generate / alter UI
function applyFilter(toGenerate) {

    toGenerate.forEach(function(id) {
        generateFilterUI(id, dataFeed[id]);
    });

    Object.keys(filter).forEach(function(id) {
        alterFilterUI(id, filter[id]);
    });

    displayAlbumInfos(dataFeed.albumInfos);
}

//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {
        return processLibAsJSON(e.target.responseText);
    };
    request.open('GET', clientURLLibrary, true);
    request.send(null);
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

    //instantiate initial filterUi
    applyFilter(['genreUI']);

    applyCompareDateBulk();

    //end loading, start animations...
    hideLoader().then(function() {
        showApp().then(function() {
            removeLoader();
        });
    });
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

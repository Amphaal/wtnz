////
////DATA FEED
////
function generateDataFeeds(lib) {  

    return {
        genreUI : getGenreUIDataFeed(lib),
        artistUI : getArtistUIDataFeed(lib),
        albumUI : getAlbumUIDataFeed(lib),
        albumInfos : getAlbumInfosDataFeed(lib),
        searchBand : getSearchBandDataFeed(lib),
        statsAlbums : getStatsAlbumsDataFeed(lib),
        statsArtists : getStatsArtistsDataFeed(lib),
        feedUploads : getLatestUploadsList(lib)
    };
}

//genreUI
function getGenreUIDataFeed(lib) {
    return function() {
        return albumsByGenreCount(lib);
    };
}

//artistUI
function getArtistUIDataFeed(lib) {
    return function() {
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
}

//albumUI
function getAlbumUIDataFeed(lib){
    return function() {
        let filterCriteria = filter['artistUI'];
        if(!filterCriteria) return;
    
        let apa = albumsByArtistsList(lib);
        let source = apa[filterCriteria]['Albums'];
        
        return Object.keys(source).reduce(function(result, current) {
            result[current] = source[current]['Year'];
            return result;
        }, {});
    };
}

//albumInfos
function getAlbumInfosDataFeed(lib) {
    return function() {
        let fArtist = filter['artistUI'];
        let fAlbum = filter['albumUI'];
        if(!fArtist || !fAlbum) return;
    
        let obj = albumsByArtistsList(lib)[fArtist]['Albums'][fAlbum];
        obj['Album'] = fAlbum;
        obj['Artist'] = fArtist;
        return obj;
    };
}

//searchBand
function getSearchBandDataFeed(lib){
    return function(filterCriteria) {
        if(!filterCriteria) return;
        
        slugFc = slugify(filterCriteria);
    
        let source = albumsByArtistsList(lib);
        let slugs = slugifiedArtists(lib);

        let results = Object.keys(source).reduce(function(total, current) {
            
            let searchIndex = slugs[current].indexOf(slugFc);
            
            if(searchIndex > -1)  {
                total[current] = {
                    Genres :  source[current]["Genres"],
                    sIndexRange : searchBand_foundRange(
                        current,
                        slugFc,
                        searchIndex
                    )
                };
            }
    
            return total;
        }, {});
    
        return Object.keys(results).length ? results : null;
    };
}

//statsAlbums
function getStatsAlbumsDataFeed(lib) {
    return function () {
        return albumsByGenreCount(lib);
    };
}

//statsArtists
function getStatsArtistsDataFeed(lib) {
    return function () {
        return artistsByGenreCount(lib);
    };
}

//feedUploads
function getLatestUploadsList(lib) {
    return function () {
        
        //prepare
        let data = albumsByIdList(lib);
        let toarr = Object.keys(data).map(function(id) {
            return [id, data[id]];
        });

        //desc sort
        toarr = toarr.sort(function(a,b) {
            let aDate = a[1]['DateAdded'];
            let bDate = b[1]['DateAdded']
            return (bDate < aDate) ? -1 : ((bDate > aDate) ? 1 : 0);
        });

        //limit
        toarr = toarr.slice(0, 100);

        //insert intervals
        let dateNow = new moment();
        toarr = toarr.reduce(function(total, curr){
            let interval = compareDateFromNomHumanized(curr[1]['DateAdded'], dateNow);
            
            if(!total[interval]) total[interval] = []
            total[interval].push(curr[1]);

            return total;
        }, {});    

        return toarr;
    };
}


///
/// data processing functions
///

function artistsByGenreList(lib) {
    return lib['arbgl'];
}

function artistsByGenreCount(lib) {
    return lib['arbgc'];
}

function albumsByGenreList(lib) {
    return lib['albgl'];
}

function albumsByGenreCount(lib) {
    return lib['albgc'];
}

function albumsByArtistsList(lib) {
    return lib['abal'];
}

function slugifiedArtists(lib) {
    return lib['slug'];
}

function albumsByIdList(lib) {
    return lib['glul'];
}


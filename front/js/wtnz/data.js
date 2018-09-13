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
        statsArtists : getStatsArtistsDataFeed(lib)
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
        return obj;
    };
}

//searchBand
function getSearchBandDataFeed(lib){
    return function(filterCriteria) {
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
    };
}

//statsAlbums
function getStatsAlbumsDataFeed(lib) {
    return function () {
        return descSortObj(albumsByGenreList(lib));
    };
}

//statsArtists
function getStatsArtistsDataFeed(lib) {
    return function () {
        return descSortObj(artistsByGenreList(lib));
    };
}


///
/// data processing functions
///

/*artistsByGenre*/
var arbgl = null;
function artistsByGenreList(lib) {
    if(!arbgl) arbgl = lib.reduce(function(total, currentVal) {
        let genre = titleCase(currentVal['Genre']);
        let artist = currentVal['Album Artist'];

        if (total[genre] == undefined) {
            total[genre] = new Set();
        }

        total[genre].add(artist);

        return total;
    }, {});

    return arbgl;
}

/*albumsByGenreList*/
var albgl = null;
function albumsByGenreList(lib) {

    //reduce uniques albums by genres
    if(!albgl) albgl = lib.reduce(function(total, currentVal) {
        let genre = titleCase(currentVal['Genre']);
        let albumId = currentVal['Album'] + '_' + currentVal['Album Artist'] + '_' + currentVal['Year'];

        if (total[genre] == undefined) {
            total[genre] = new Set();
        }

        total[genre].add(albumId);

        return total;
    }, {});

    return albgl;
}

/*albumsByGenreCount*/
var abgc = null;
function albumsByGenreCount(lib) {
    let base = albumsByGenreList(lib);
    
    if(!abgc) abgc = Object.keys(base)
    .reduce(function(result, key) {
        let set = base[key];
        result[key] = set.size;
        return result;
    }, {});

    return abgc;
}

/*albumsByArtistsList*/
var abal = null;
function albumsByArtistsList(lib) {
    if(!abal) abal = lib.reduce(function(total, currentVal) {
        
        //prepare
        let artist = currentVal['Album Artist'];
        let album = currentVal['Album'];
        let genre = titleCase(currentVal['Genre']);
        let year = currentVal['Year'];
        let trackNo = currentVal['Track Number'];
        let trackName = currentVal['Name'];
        let dateAdded = currentVal['Date Added'];

        //if first occurence artist
        if(total[artist] == undefined) {
            total[artist] = {
                "Genres" : new Set(),
                "Albums" : {}
            }
        }

        //add genre
        total[artist]["Genres"].add(genre);

        //if first occurence album
        if (total[artist]["Albums"][album] == undefined) {
            total[artist]["Albums"][album] = {
                "Year" : year,
                "Genre" : genre,
                "Tracks" : {},
                "DateAdded" : dateAdded
            };
        }

        //add track
        total[artist]["Albums"][album]["Tracks"][trackNo] = trackName;

        return total;
    }, {});

    return abal;
}

///
/// MusicBrainz Integration
/// 

var request_qmbfac = null;
function queryMusicBrainzForAlbumCover() {
    return new Promise(function(resolve, reject){

        let urlBase =  'http://musicbrainz.org/ws/2/release/?limit=1&fmt=json&query=';
        let queryObj = {
            release : filter["albumUI"],
            artist :  filter["artistUI"]
        };
    
        //if elements missing, abort
        if (!queryObj.release || !queryObj.artist) reject();
    
        //join and make request query
        let queryArr = Object.keys(queryObj).map(function(e) {
            return e.toLowerCase() + ':' + queryObj[e].toLowerCase();
        });
        let query = encodeURI(queryArr.join(" AND "));
        query = urlBase + query;
        
        //begin request and terminate non-finished previous request
        if(request_qmbfac) request_qmbfac.abort();
        request_qmbfac = new XMLHttpRequest();

        request_qmbfac.onloadend = function(e) {
            let text = e.currentTarget.responseText;
            let obj = JSON.parse(text);
            let imgUrl = mbQueryCoverArtAPI(obj.releases);
            imgUrl ? resolve(imgUrl) : reject();
        };
        request_qmbfac.open('GET', query, true);
        request_qmbfac.send(null);
    });
}

function mbQueryCoverArtAPI(mbReleasesArray) {
    if (!mbReleasesArray.length) return;
    let urlBase = 'http://coverartarchive.org/release/{mbid}/front-250';
    return urlBase.replace('{mbid}',mbReleasesArray[0].id);
}
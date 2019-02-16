////
////DATA FEED
////
function generateDataFeeds(lib) {  

    //preload
    slugifiedArtists(lib);

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
        
        slug_fc = slugify(filterCriteria);
    
        let source = albumsByArtistsList(lib);
        let slugs = slugifiedArtists(lib);

        let results = Object.keys(source).reduce(function(total, current) {
            
            let sIndex = slugs[current].indexOf(slug_fc);
            if(sIndex > -1)  {
                let fCritLen = filterCriteria.length;
                let sIndexEnd = fCritLen + sIndex;
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
        toarr = toarr.slice(0, 50);

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
        let discNumber = currentVal['Disc Number'];

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
        total[artist]["Albums"][album]["Tracks"][discNumber + '.' + trackNo] = trackName;

        return total;
    }, {});

    return abal;
}

var slug_a = null;
function slugifiedArtists(lib) {
    let base = albumsByArtistsList(lib);

    if(!slug_a) slug_a = lib.reduce(function(total, currentVal) {
        let artist = currentVal["Album Artist"];
        total[artist] = slugify(artist);
        return total;
    }, {});

    return slug_a;
}


/*latestUploadsList*/
var glul = null;
function albumsByIdList(lib) {
    if(!glul) glul = lib.reduce(function(total, currentVal, index, arr){
       
        // prepare
        let albumId = currentVal['Album'] + '_' + currentVal['Album Artist'] + '_' + currentVal['Year'];
        let newDate = currentVal['Date Added'];
        
        if(!total[albumId]) {
            total[albumId] = {
                "Album" : currentVal['Album'],
                "Artist" : currentVal['Album Artist'],
                "Year" : currentVal['Year'],
                "DateAdded" : newDate,
                "Genre" : titleCase(currentVal['Genre'])
            };
        } else {
            let oldDate = total[albumId]['DateAdded'];
            if (newDate > oldDate) total[albumId]['DateAdded'] = newDate;
        }

        return total;
    }, {});
    return glul;
}

///
/// MusicBrainz Integration
/// 

var request_qmbfac = {};
function queryMusicBrainzForAlbumCover(idProcess, album, artist) {
    if (!idProcess) idProcess = 'albumInfos';

    return new Promise(function(resolve, reject){

        let urlBase =  'https://musicbrainz.org/ws/2/release-group/?limit=1&fmt=json&query=';
        let queryObj = {
            release : album || filter["albumUI"],
            artist :  artist || filter["artistUI"]
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
        if(request_qmbfac[idProcess]) request_qmbfac[idProcess].abort();
        request_qmbfac[idProcess] = new XMLHttpRequest();

        request_qmbfac[idProcess].onloadend = function(e) {
            let text = e.currentTarget.responseText;
            let obj = JSON.parse(text);
            let imgUrl = mbQueryCoverArtAPI(obj['release-groups']);
            imgUrl ? resolve(imgUrl) : reject();
        };
        request_qmbfac[idProcess].open('GET', query, true);
        request_qmbfac[idProcess].send(null);
    });
}

function mbQueryCoverArtAPI(mbReleasesArray) {
    if (!mbReleasesArray.length) return;
    let urlBase = 'https://coverartarchive.org/release-group/{mbid}/front-250';
    return urlBase.replace('{mbid}',mbReleasesArray[0].id);
}

function linkToYoutube(artist, albumOrTitle) {
    //link to YT
    let yt_query = 'https://www.youtube.com/results?search_query=';
    let album_query =  (artist+ ' ' + albumOrTitle).replace('  ', ' ').replace(' ', '+').toLowerCase();
    return yt_query + album_query;
}
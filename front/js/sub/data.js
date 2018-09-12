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
            let text = e.target.responseText;
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
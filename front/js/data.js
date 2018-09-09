/*artistsByGenre*/
function artistsByGenreList(lib) {
    return lib.reduce(function(total, currentVal) {
        let genre = titleCase(currentVal['Genre']);
        let artist = currentVal['Album Artist'];

        if (total[genre] == undefined) {
            total[genre] = new Set();
        }

        total[genre].add(artist);

        return total;
    }, {});
}

/*albumsByGenreList*/
function albumsByGenreList(lib) {

    //reduce uniques albums by genres
    return lib.reduce(function(total, currentVal) {
        let genre = titleCase(currentVal['Genre']);
        let albumId = currentVal['Album'] + '_' + currentVal['Album Artist'] + '_' + currentVal['Year'];

        if (total[genre] == undefined) {
            total[genre] = new Set();
        }

        total[genre].add(albumId);

        return total;
    }, {});
}

/*albumsByGenreCount*/
function albumsByGenreCount(lib) {
    let base = albumsByGenreList(lib);
    
    return Object.keys(base)
    .reduce(function(result, key) {
        let set = base[key];
        result[key] = set.size;
        return result;
    }, {});
}


/*albumsByArtistsList*/
function albumsByArtistsList(lib) {
    return lib.reduce(function(total, currentVal) {
        
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
}
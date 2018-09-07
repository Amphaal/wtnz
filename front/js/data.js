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

/*albumsByGenreCount*/
function albumsByGenreCount(lib) {

    //reduce uniques albums by genres
    let uniqueAlbumsByGenre = lib.reduce(function(total, currentVal) {
        let genre = titleCase(currentVal['Genre']);
        let albumId = currentVal['Album'] + '_' + currentVal['Album Artist'] + '_' + currentVal['Year'];

        if (total[genre] == undefined) {
            total[genre] = new Set();
        }

        total[genre].add(albumId);

        return total;
    }, {});

    return Object.keys(uniqueAlbumsByGenre).reduce(function(total, currentVal) {
        total[currentVal] = uniqueAlbumsByGenre[currentVal].size
        return total;
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
                "Tracks" : {}
            };
        }

        //add track
        total[artist]["Albums"][album]["Tracks"][trackNo] = trackName;
        
        return total;
    }, {});
}

//desc sorting of obj
function descSortObj(objToSort) {
    
    //prepare
    let keys = Object.keys(objToSort);

    //array count for sorting
    let toArrayForm = keys.reduce(function(total, currentVal) {
        total.push({
            name : currentVal,
            value : objToSort[currentVal].size || objToSort[currentVal]
        })
        return total;
    }, []);

    //descending sort
    return toArrayForm.sort(function (a, b) {
        return b.value - a.value;
    });
}

function titleCase(str) {
    let splitStr = str.toLowerCase().split(' ');
    for (let i = 0; i < splitStr.length; i++) {
        // You do not need to check if i is larger than splitStr length, as your for does that for you
        // Assign it back to the array
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
    }
    // Directly return the joined string
    return splitStr.join(' '); 
 }
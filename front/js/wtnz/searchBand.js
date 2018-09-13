///
///ENTRYPOINT
///

//search band through head input
function searchBand(event) {
    let criteria = event.target.value;
    let data = dataFeeds.searchBand(criteria);
    renderSearchResults(criteria, data);
}

///
///HELPERS UI
///

//toggle search results panel beneath the search input
function toggleSearchResults(event) {
    let target = document.querySelector("#searchBand .searchResults");
    event.type == "focus" && event.target.value ? target.classList.add('show') : target.classList.remove('show');
}

//render
function renderSearchResults(criteria, data) {
    
    let target = document.querySelector("#searchBand .searchResults");
    let ctnr = document.querySelector("#searchBand .search");

    //purge current results
    while (target.firstChild) {
        target.removeChild(target.firstChild);
    }

    //hide or show
    criteria ? target.classList.add('show') : target.classList.remove('show'); 

    //if results
    if (data) {

        let arr = Object.keys(data);

        //add number of elements found
        ctnr.dataset.ef = arr.length;

        //generate results items
        arr.reduce(function(total, current) {
            
            //result container
            let resultDiv = document.createElement('div');
            resultDiv.classList.add('result');
            resultDiv.setAttribute('title', current);
            resultDiv.onmousedown = updateFilter;

            //get first genre of artist
            let firstGenre = [];
            data[current]["Genres"].forEach(function(val) {firstGenre.push(val);});
            firstGenre = firstGenre.shift();
            
            //add filter params
            let filterObj = {
                genreUI : firstGenre,
                artistUI : current
            };
            resultDiv.dataset.nFilter = JSON.stringify(filterObj);

            //range display 
            let range = data[current].sIndexRange;
            let b = current.substring(0, range[0]);
            let c = current.substring(range[0], range[1]);
            let a = current.substring(range[1], current.length); 

            //bind to spans
            let spanElems = [b,c,a].map(function(curr) {
                let elem = document.createElement('span');
                elem.innerHTML = curr;
                return elem;
            });
            spanElems[1].classList.add('f');
            spanElems.forEach(function(e) {
                resultDiv.appendChild(e);
            });

            //return div
            total.push(resultDiv);
            return total;
        }, [])
        .forEach(function(item) { target.appendChild(item); });

    } else {

        //remove elements found indicator
        ctnr.removeAttribute('data-ef');

        //no results
        let noResultDiv = document.createElement('div');
        noResultDiv.classList.add("nr");
        noResultDiv.innerHTML = 'No results found';
        target.appendChild(noResultDiv);
    }
}

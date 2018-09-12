//hide loader bar
function hideLoader() {
    return new Promise(function(resolve, reject) {
        let loader = document.getElementById("loader");
        loader.classList.remove("fadeIn");
        loader.classList.add("fadeOut");

        return loader.addEventListener(whichAnimationEvent(), function lele(e) {
            loader.removeEventListener(whichAnimationEvent(), lele, false);
            resolve();
        }, false);
    });
}

//remove loader from layout
function removeLoader() {
    let target = document.getElementById("loader-container");
    target.parentElement.removeChild(target);
}

//show content
function showApp() {
    return new Promise(function(resolve, reject) {
        let content = document.getElementById("wtnz");
        content.classList.add("animated");
        content.classList.add("fadeIn");

        return content.addEventListener(whichAnimationEvent(), function lele2(e) {
            content.removeEventListener(whichAnimationEvent(), lele2, false);
            resolve();
        }, false);
    });
}

//update loader bar
function updateProgress(evt){
    if (evt.lengthComputable){
        let percentComplete = (evt.loaded / evt.total) * 100;  
        document.getElementById("loader-bar").style.width = percentComplete + "%";
    } 
}

function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

//toggle search results panel beneath the search input
function toggleSearchResults(event) {
    let target = document.querySelector("#searchBand .searchResults");
    event.type == "focus" && event.target.value ? target.classList.add('show') : target.classList.remove('show');
}

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

//generate genres UI
function generateFilterUI(id, dataFunc) {
    
    let target = document.querySelector('#' + id + ' .list');
    if (dataFunc == null) return false;

    let data = dataFunc();

    //purge current results
    while (target.firstChild) {
        target.removeChild(target.firstChild);
    }
    
    //if there is any data
    if (data) {
        Object.keys(data).reduce(function(result, current) {
            let item = document.createElement('div');
            item.innerHTML = current;
            item.dataset.count = data[current];
            item.dataset.nFilter = current;
            item.onmousedown = updateFilter;
            result.push(item);
            return result;
        }, [])
        .sort(function(a,b) {return b.dataset.count - a.dataset.count;})
        .forEach(function(item) { target.appendChild(item);});

        target.dataset.maxWidth = target.clientWidth;
    }

    return true;
}

//prepare sub elements of the filter
function prepareFilterUI(id) {
    let target = document.getElementById(id);
    target.classList.add("filterUI");
    
    let ph = document.createElement('div');
    ph.classList.add("ph");
    target.appendChild(ph);

    let list = document.createElement('div');
    list.classList.add("list");
    target.appendChild(list);
}

function alterFilterUI(id, filterCriteria) {

    //filterUI
    let ui = document.getElementById(id);

    //ui filter greying not selected
    let list = document.querySelector('#' + id + ' .list');
    list.childNodes.forEach(function(curr) {
        let nodefilter = curr.dataset.nFilter;
        nodefilter == filterCriteria ? curr.classList.add("selected") : curr.classList.remove("selected");
    });

    //reset manual widths for animations
    let mWidthQuery = 'style[data-ct="'+id+'"]';
    let mWidth = document.querySelector(mWidthQuery);
    if(mWidth) mWidth.parentElement.removeChild(mWidth);

    //ui filter acordeon effect + placeholder filing
    let ph = document.querySelector('#' + id + ' .ph');
    if(filterCriteria) {

        ui.classList.add("hasSelection");
        ph.innerHTML = filterCriteria + ' ' + String.fromCharCode(0x00BB); //fill placeholder
        
        //set manual widths for animations 
        let min = (ph.clientWidth / getRootElementFontSize());
        let max = (list.dataset.maxWidth / getRootElementFontSize());
        let styleCarrier = document.createElement('style');
        styleCarrier.dataset.ct = id;
        styleCarrier.innerHTML += '#' + id + ':hover {width : ' + Math.max(min, max) + 'rem !important;}';
        styleCarrier.innerHTML += '#' + id + '{width : ' + Math.min(min, max) + 'rem;}';
        document.body.appendChild(styleCarrier);

    } else {
        ui.classList.remove("hasSelection");
        ph.innerHTML = ""; //resets placeholder

    } 
    
    //ui filter prefix
    list.childElementCount || ph.innerHTML ? ui.classList.add("active") :  ui.classList.remove("active");
}

//handle album infos rendering
function displayAlbumInfos(dataFunc) {
    
    let data = dataFunc(); 

    let target = document.getElementById('albumInfos');
    
    let aYear = document.getElementById('aYear');
    let aGenre = document.getElementById('aGenre');
    let aDateAdded = document.getElementById('aDateAdded');
    let aTracks = document.getElementById('aTracks');
    let aTitle = document.getElementById('aTitle');
    
    //purge
    [aYear, aGenre, aDateAdded, aTracks].forEach(function(e) {
            e.innerHTML = '';
            e.removeAttribute('title');
    });

    //specific image purge
    let aImage = document.getElementById('aImage');
    aImage.firstChild.removeAttribute('src');
    aImage.classList.remove('noImgFound');
    aImage.classList.add('searchingCover');

    target.classList.remove('show');

    //then fill
    if(data) {

        //specific async image handler
        aImage.classList.add('searchingCover');
        queryMusicBrainzForAlbumCover().then(function(imgUrl) {
            aImage.classList.remove('searchingCover');
            aImage.firstChild.setAttribute('src', imgUrl);
        }, function() {
            brokenImgFr(aImage.firstChild, aImage);
        });

        aYear.innerHTML = data['Year'];
        aGenre.innerHTML = data['Genre'];
        aTitle.innerHTML = data['Album'];

        aDateAdded.innerHTML = compareDateFromNomHumanized(data['DateAdded']);
        aDateAdded.setAttribute('title', data['DateAdded']);
        
        Object.keys(data['Tracks']).forEach(function(trackId) {
            aTracks.innerHTML += '<li>' + data['Tracks'][trackId] + '</li>';
        });
        
        target.classList.add('show');
    }
}


function brokenImgFr(eImg, eContainer) {
    eContainer.classList.remove('searchingCover');
    eContainer.classList.add('noImgFound');
    eImg.removeAttribute('src');
}

function brokenImg(event) {
    brokenImgFr(event.target, event.target.parentElement);
}
///
///ENTRYPOINT
///

//update filter from UI action
function updateFilter(event) {
    
    //prevent bubbling to .filterUI level event
    event.stopPropagation(); 

    //update FilterUIs by required values
    let newFilters = getNewFiltersFromUI(event);
    let IDsToReload = applyNewFilters(newFilters);
    updateFilterUIs(IDsToReload);

    //dynamically generate albums infos and scrolls to it if necessary
    displayAlbumInfos(dataFeeds.albumInfos).then(function(albumInfosElem) {
        if(newFilters["albumUI"]) transitionToAlbumInfos(albumInfosElem);
    });

    //if must reload something, scroll to this last something
    if (!newFilters["albumUI"]) {
        let target = document.querySelector('.filterUI.active:not(.hasSelection)');
        wtnzScroll(target, -30); //add margin-top for ::before tag element
    }
}


///
///DATA PROCESSING HELPERS
///

//from UI action, format the new filters to apply
function getNewFiltersFromUI(event) {

    //prepare
    let dataFilters = event.currentTarget.dataset.nFilter;
    let filterUIBound = event.currentTarget.parentNode.parentNode;

    //handle single arg with primitive data, essentially from button click
    if (!IsJsonString(dataFilters) && filterUIBound.classList.contains('filterUI')) {

        //preapre
        let dFilter = dataFilters;
        let filterCat = filterUIBound.id;

        //if dest filter is equal to current, it means that the user wants to remove it
        if (dFilter && filter[filterCat] == dFilter) dFilter = null;

        //update dataFilters obj
        dataFilters = {};
        dataFilters[filterCat] = dFilter;

    } else {
        //parse filter string to Obj
        dataFilters = JSON.parse(dataFilters);
    }

    return dataFilters;
}

//apply filters and return UI IDs that need a reload
function applyNewFilters(newFilters) {

    let resetFollowing = false;
    return Object.keys(filter).reduce(function (toGenerate, id, index, initalArray) {

        //prepare
        let newValue = newFilters[id];
        let oldValue = filter[id];
        let newValIsUndef = typeof newValue === 'undefined';

        if (oldValue == newValue) return toGenerate; //if identical, skip
        if (newValIsUndef && !resetFollowing) return toGenerate; //if no modification already occured before and no new val, skip

        //if previous have been modified, no new values have been specified and the old value is something, force a reset
        if (resetFollowing && newValIsUndef && oldValue) newValue = null;

        //sets new value
        filter[id] = newValue || null;

        //assumes a modification has occured
        resetFollowing = true;

        //add the next existing id to regeneration queue
        let nextId = initalArray[index + 1];
        if (nextId) toGenerate.push(nextId);
        return toGenerate;
    }, []);
}

///
///HELPERS UI
///

//prepare sub elements of the filterUIs
function prepareFilterUIs(IDs) {
    IDs.forEach(function (id) {
        let target = document.getElementById(id);
        target.classList.add("filterUI");

        let ph = document.createElement('span');
        ph.classList.add("ph");
        target.appendChild(ph);

        let list = document.createElement('div');
        list.classList.add("list");
        target.appendChild(list);

        //permit to open the list when something has already been selected on the filter
        target.onmousedown = function(e) {
            target.classList.contains('open') ? target.classList.remove('open') : target.classList.add('open');
        };

    });
}

//helper func
function updateFilterUIs(arrayOfIDs) {
    arrayOfIDs.forEach(function (id) {
        generateFilterUI(id, dataFeeds[id]);
    });

    Object.keys(filter).forEach(function (id) {
        alterFilterUI(id, filter[id]);
    });

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
        Object.keys(data).reduce(function (result, current) {
            let item = document.createElement('span');
            item.innerHTML = current;
            item.dataset.count = data[current];
            item.dataset.nFilter = current
            item.onmousedown = updateFilter;
            result.push(item);
            return result;
        }, [])
            .sort(function (a, b) { return b.dataset.count - a.dataset.count; })
            .forEach(function (item) { target.appendChild(item); });
    }

    return true;
}

//alter
function alterFilterUI(id, filterCriteria) {

    //prepare
    let ui = document.getElementById(id);
    let list = document.querySelector('#' + id + ' .list');
    let ph = document.querySelector('#' + id + ' .ph');

    //ui filter greying not selected
    list.childNodes.forEach(function (curr) {
        let nodefilter = curr.dataset.nFilter;
        nodefilter == filterCriteria ? curr.classList.add("selected") : curr.classList.remove("selected");
    });

    //ui filter acordeon effect + placeholder filing
    if (filterCriteria) {
        ui.classList.add("hasSelection");
        ui.classList.remove("open");
        ph.innerHTML = filterCriteria;
    } else {
        ui.classList.remove("hasSelection");
        ph.innerHTML = ""; //resets placeholder
    }

    //ui filter prefix
    list.childElementCount || ph.innerHTML ? ui.classList.add("active") : ui.classList.remove("active");

    //apply for animations
    applyManualSizesFilterUIs(id)();
}


//animation scroll
function transitionToAlbumInfos(aiElem) {
    //scroll to albumInfos...
    wtnzScroll(aiElem, -10);
}

///
/// resize functions
///

function applyManualSizesFilterUIs(id) {
    return function () {
        
        //prepare
        let list = document.querySelector('#' + id + ' .list');
        let ph = document.querySelector('#' + id + ' .ph');
        if(!list || !ph) return;

        //reset manual widths for animations
        let mWidthQuery = 'style[data-ct="' + id + '"]';
        let mWidth = document.querySelector(mWidthQuery);
        if (mWidth) mWidth.parentElement.removeChild(mWidth);

        //calculate ceil + small margin upper
        let ceil = function (val) {
            return (Math.ceil(val * 10) / 10) + 0.1;
        };

        //helper function to calculate heights / widths
        let genCss = function (minOrMax, dimension) {
            let prop = "scroll" + dimension;
            let val = null;
            switch (minOrMax) {
                case "min":
                    val = ceil(((ph[prop] || list[prop] + 1) / getRootElementFontSize()));
                    return '#' + id + '{' + dimension.toLowerCase() + ' : ' + val + 'rem;}';
                case "max":
                    val = ceil(((list[prop] + 1) / getRootElementFontSize()));
                    return '#' + id + '.open {' + dimension.toLowerCase() + ' : ' + val + 'rem;}';
            }
        }

        //force relative positionning to get true height / width of list
        list.style.position = "relative"; 

        //set manual widths and heights for animations 
        let styleCarrier = document.createElement('style');
        styleCarrier.dataset.ct = id;
        styleCarrier.innerHTML += genCss("min", "Width");
        styleCarrier.innerHTML += genCss("max", "Width");
        styleCarrier.innerHTML += genCss("min", "Height");
        styleCarrier.innerHTML += genCss("max", "Height");

        list.style.position = null; //unforce

        document.body.appendChild(styleCarrier);
    }
}

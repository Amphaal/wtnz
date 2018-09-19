function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

//scrolls to element, taking in account the sticky header
function wtnzScroll(elem, correct) {
    if(!elem) return;
    let sticky = document.querySelector('header');
    let relativeDocumentOffset = elem.getBoundingClientRect().top + window.scrollY;
    let elemPos = relativeDocumentOffset - sticky.clientHeight;
    window.scroll(0, elemPos + (correct || 0));
}

function isVisible(elem) {
    return elem.getBoundingClientRect().top > window.scrollY;
}

///
/// resize handling
///

var timeoutResize = false;
var delayResize = 50;
var sourceHeight = window.innerHeight;
var sourceWidth = window.innerWidth;
var resizeFunctions = {
    height : [],
    width : [],
    any : []
};

//event listener with throtte
window.addEventListener('resize', function(event) {
    clearTimeout(timeoutResize);
    timeoutResize = setTimeout(resizeManualHeightsAndWidths, delayResize);
});
window.addEventListener('orientationchange', function() {
    //debugger;
    //resizeManualHeightsAndWidths();
})

function resizeManualHeightsAndWidths() {
    
    //prepare
    let newHeight = window.innerHeight;
    let newWidth = window.innerWidth;
    
    //height or width...
    resizeFunctions.any.forEach(function(func) {
        func();
    });

    //height...
    if(newHeight != sourceHeight) {
        sourceHeight = newHeight;
        resizeFunctions.height.forEach(function(func) {
            func();
        });
    }

    //width...
    if(newWidth != sourceWidth) {
        sourceWidth = newWidth;
        resizeFunctions.width.forEach(function(func) {
            func();
        });
    }

}

///
/// Img loading
///

function brokenImgFr(elem) {
    elem.classList.remove('searchingCover');
    elem.classList.add('noImgFound');
    elem.firstElementChild.removeAttribute('src');
}

function brokenImg(event) {
    brokenImgFr(event.currentTarget.parentElement);
}

function imgLoaded(event) {
    event.currentTarget.parentElement.classList.remove('searchingCover');
}

function resetImgLoader(elem) {
    elem.firstElementChild.removeAttribute('src');
    elem.classList.remove('noImgFound');
    elem.classList.add('searchingCover');
}

function updateImgLoader(elem, imgUrl) {
    elem.firstElementChild.setAttribute('src', imgUrl);
}
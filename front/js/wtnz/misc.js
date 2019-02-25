function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

//scrolls to element, taking in account the sticky header
function hNavigate(elem, correct) {
    if(!elem) return;
    //let sticky = document.querySelector('header');
    let relativeDocumentOffset = elem.getBoundingClientRect().top + window.scrollY;
    let elemPos = relativeDocumentOffset;// - sticky.clientHeight;
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

/////////////////////////
//scroll event handling//
/////////////////////////

// find direction based on last position
// null = not moved
// true = going down
// false = going up
var sueh_last_scroll_pos = 0;
function findScrollingDirection() {
        let currentDirection = null;
        let newPos = window.scrollY;
        if(newPos != sueh_last_scroll_pos) 
        {
            currentDirection = newPos > sueh_last_scroll_pos;
            sueh_last_scroll_pos = newPos;
        }
        return currentDirection ? "down" : "up";
}

var sueh_ticking = false;
function scrollUiEventHandling() {

    //declare event listener
    window.addEventListener('scroll', function(e) {

        //if no animation on run...
        let updateCl = mustHeaderUpdateAnimation(findScrollingDirection());
        if (!sueh_ticking && updateCl !== false) {
            
            //lock
            sueh_ticking = true;

            //cpu optimisation for execution
            window.requestAnimationFrame(function() {

                //animate
                updateHeaderClasses(updateCl);

                //release
                sueh_ticking = false;
            });
        }
      });
}

//find if Header should be animated
function mustHeaderUpdateAnimation(directionToCompute) {
    if (directionToCompute == null) return false;

    //prepare
    let header = document.getElementsByTagName("header")[0];
    let isHidden = header.classList.contains("toHide");
    let isSticky = header.classList.contains("sticky");
    let ooR = isHeaderOutOfReach();
    let mustSticky = "";
    let mustHide = "";

    //if user is searching
    let isSRUsed = document.querySelectorAll("header .searchResults.show").length; 
    
    //rules...
    if (isSRUsed || directionToCompute == "up") {
        mustSticky = "sticky";
    } else if (directionToCompute == "down" && ooR) {
        mustHide = "toHide";
        if(isSticky) {
            mustSticky = "sticky";
        }
    }

    //compute and compare new and old classes
    let newClasses = [mustSticky, mustHide].join(" ").trim();
    return newClasses == header.getAttribute("class") ? false : newClasses;
}

function updateHeaderClasses(updateCl) {
    document.getElementsByTagName("header")[0].setAttribute("class", updateCl);
}

function isHeaderOutOfReach() {
    let header = document.getElementsByTagName("header")[0];
    let headerHeight = header.clientHeight;
    let scrollHeight = window.scrollY;
    return scrollHeight > headerHeight;
}

function vNavigate() {
    let initial = "";
    let newPos = "translateX(-100%)";
    let target = document.getElementsByTagName('main')[0];
    let indexFocused = null;

    if(target.style.transform == initial) {
        target.style.transform = newPos;
        indexFocused = 1;
        document.body.classList.add("lock");
    } else {
        target.style.transform = initial;
        indexFocused = 0;
        document.body.classList.remove("lock");
        window.scrollTo({
            top : 0
        });
    }

    //move focus flag
    for(let i = 0; i < target.childElementCount; i++) {
        if(i == indexFocused) {
            target.children[i].classList.add('focused');
        } else {
            target.children[i].classList.remove('focused');
        }
    }
}
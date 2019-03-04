

function registerXNavigateSwipeEvents() {
    var hammertime = new Hammer(document.body);

    hammertime.on('swipeleft swiperight', function(ev) {
        hNavigate(ev.direction);
    });

    document.addEventListener("scroll", onScroll);
}

/*Prevent Scroll Event triggering */
function preventSET(inBetweenPromise) {
    //temporary disabling event listening
    document.removeEventListener("scroll", onScroll);
    return inBetweenPromise.then(function() {
        document.addEventListener("scroll", onScroll);
    });
}

function onScroll(ev) {
    if(Math.abs(checkScrollSpeed()) < 10) return;
    headerToggle();
}

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(function() {return resizeFeed().applyNewHeight()});
    resizeFunctions.width.push(function() {return resizeShout().applyNewHeight()});
    Object.keys(_discoverFilter).forEach(function(id) {
        resizeFunctions.any.push(applyManualSizesFilterUIs(id));
    })
}

function _resizeShutter(targetId, reason, nextType) {
    let target = document.getElementById(targetId);
    let heightSwitch = reason ? target.scrollHeight + "px" : "";
    let changed = target.style.maxHeight != heightSwitch;

    let updateTargetMaxHeight = function() {
        window.requestAnimationFrame(function() {
            target.style.maxHeight = heightSwitch;
        });
    };
    
    return {
        next : nextType == "changed" ? changed : heightSwitch,
        applyNewHeight : changed ? updateTargetMaxHeight : function() {return;}
    };
}

function _toggleShutter(targetId, resizeInstructionsGetter, onResizeTransitionEnd) {

    return new Promise(function(resolve) {

        let reziseInstructions = resizeInstructionsGetter();

        if (reziseInstructions.next) {
            let onceend = waitTransitionEnd(document.getElementById(targetId), reziseInstructions.applyNewHeight);
            if(onResizeTransitionEnd) {
                onceend.then(onResizeTransitionEnd).then(resolve);
            } else {
                onceend.then(resolve);
            }
        } else {
            reziseInstructions.applyNewHeight();
            resolve();
        }
    });
}


function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

//scrolls to element, taking in account the sticky header
function vNavigate(elem, correct) {
    if(!elem) return;
    //let sticky = document.querySelector('header');
    let relativeDocumentOffset = elem.getBoundingClientRect().top + window.scrollY;
    let elemPos = relativeDocumentOffset;// - sticky.clientHeight;
    window.scroll(0, elemPos + (correct || 0));
}

function _isInClientViewField(elem) {
    return elem.getBoundingClientRect().top > window.scrollY;
}

///
/// resize handling
///


var timeoutResize = false;
var delayResize = 100;
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
    window.scrollTo({left : 0});
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

var checkScrollSpeed = (function(settings){
    settings = settings || {};

    var lastPos, newPos, timer, delta, 
        delay = settings.delay || 100; // in "ms" (higher means lower fidelity )

    function clear() {
      lastPos = null;
      delta = 0;
    }

    clear();

    return function(){
      newPos = window.scrollY;
      if ( lastPos != null ){ // && newPos < maxScroll 
        delta = newPos -  lastPos;
      }
      lastPos = newPos;
      clearTimeout(timer);
      timer = setTimeout(clear, delay);
      return delta;
    };
})();

///
/// Img loading
///

function brokenImgFr(elem) {
    
    waitTransitionEnd(elem, function() {
        elem.classList.add("fo");
    }).then(function() {
        elem.firstElementChild.removeAttribute('src');
        elem.classList.remove('searchingCover');
        elem.classList.remove('fo');
        elem.classList.add('noImgFound');
    });
}

function brokenImg(event) {
    brokenImgFr(event.currentTarget.parentElement);
}

function imgLoaded(event) {
    let imgLoader = event.currentTarget.parentElement;
    
    waitTransitionEnd(imgLoader, function() {
        imgLoader.classList.add("fo");
    }).then(function() {
        imgLoader.classList.remove('searchingCover');
        imgLoader.classList.remove('fo');
    });
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
function headerToggle() {
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

function hNavigate(direction) {
    let target = document.getElementsByTagName('main')[0];
    let maxChildren = target.childElementCount;
    let maxIndex = maxChildren - 1;
    let actualIndex = target.getAttribute("data-index") || 0;
    let targetIndex = null;

    //direction to go
    if(direction == null) { //automatic switch
        targetIndex = Number(actualIndex) ? 0 : 1;
    } else if (direction == 2){
        targetIndex = actualIndex + 1;
    } else if (direction == 4) {
        targetIndex = actualIndex - 1;
    }

    //so dont move
    if(targetIndex == actualIndex || targetIndex == null) return;
    if(targetIndex > maxIndex || targetIndex < 0) return;

    //specific to target
    if(targetIndex == 1) { document.body.classList.add("lock"); } else { document.body.classList.remove("lock"); } //remove Vscroll on connect
    
    //move...
    target.style.transform = "translateX(-" + String(targetIndex * 100) + "%)";
    target.setAttribute("data-index", targetIndex);
    window.scrollTo({top : 0}); //scroll back to top

    //reset focus flag
    for(let i = 0; i < maxChildren; i++) {
        if(i == targetIndex) {
            target.children[i].classList.add('focused');
        } else {
            target.children[i].classList.remove('focused');
        }
    }
}
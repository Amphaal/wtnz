function changeLang(event) {
    let newLang = event.currentTarget.getAttribute("data-lang");
    ezPOST({
        set_lang : newLang
    });
}

function removeNotification(selector) {
    document.querySelectorAll(selector).forEach(function(elem) {
        waitAnimationEnd(elem, function() {
            elem.classList.add("notifOut");
        }).then(function() {
            elem.classList.remove("notif");
            elem.classList.remove("notifOut");
        });
    });
}

function ezPOST(data) {
    let form = document.createElement("form");
    form.setAttribute("method", "POST");
    
    Object.keys(data).map(function(key) {
        let iElem = document.createElement('input');
        iElem.setAttribute("type", "hidden");
        iElem.setAttribute("name", key);
        iElem.setAttribute("value", data[key]);
        return iElem;
    }).forEach(function (item) { form.appendChild(item); });
    
    document.body.appendChild(form);
    form.submit();
}

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
    return inBetweenPromise.then(function(elem) {
        document.addEventListener("scroll", onScroll);
        return elem;
    });
}

function onScroll() {
    let ss = checkScrollSpeed();
    if(Math.abs(ss) < 10) return;
    headerToggle();
}

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(function() {return resizeFeed().applyNewHeight()});
    resizeFunctions.width.push(function() {return resizeShout().applyNewHeight()});
    resizeFunctions.any.push(function() {
        return headerToggle();
    });
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

function _toggleShutter(targetId, resizeInstructionsGetter) {

    let anim = new Promise(function(resolve) {

        let reziseInstructions = resizeInstructionsGetter();

        waitTransitionEnd(
            document.getElementById(targetId), 
            reziseInstructions.applyNewHeight
        ).then(resolve);

    });

    return anim;
}


function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

//scrolls to element
function vNavigate(elem, correct) {
    
    if(!elem) resolve();
    let relativeDocumentOffset = elem.getBoundingClientRect().top + window.scrollY;
    let elemPos = relativeDocumentOffset + (correct || 0);
    let isScrollerAtDest = function() {return elemPos == window.scrollY || document.body.scrollHeight == window.innerHeight;};

    if(isScrollerAtDest()) {
        updateHeaderClasses("sticky"); return;
    }

    //push the menu up
    let rollbackMenu = function() {
        return waitTransitionEnd(
            document.getElementsByTagName("header")[0], 
            function() {
                updateHeaderClasses("sticky toHide");
        }
        ).then(function() {
            updateHeaderClasses("");
        });
    };

    //scroll to destination
    let scrollToDest = function() {
        return new Promise(function(resolve) {
            
            //action
            window.scroll(0, elemPos);
            
            //wait...
            let timeoutMs = 0;
            let timeoutStepMs = 100;
            let timeoutLimit = function() {timeoutMs = timeoutMs + timeoutStepMs; return timeoutMs > 2000;};
            var untilScrollIsOver = setInterval(function() {
                if (isScrollerAtDest() || timeoutLimit()) {
                    clearInterval(untilScrollIsOver);
                    resolve();
                }
            }, timeoutStepMs);

        });
    };

    //wait for both animations to continue
    return Promise.all([
        rollbackMenu(), 
        scrollToDest()
    ]);
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
    let followUp = null;

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

    //remove Vscroll on connect
    if(targetIndex == 1) { 
        document.body.classList.add("lock"); 

        //starter animation
        let rloader = document.getElementById("xmlRLoader");
        let firstAnim = rloader.style.maxHeight == "";
        if(firstAnim) followUp = function() {

            removeNotification(".connect-side");

            waitTransitionEnd(rloader, function() {
                rloader.style.maxHeight = rloader.scrollHeight + "px";
            }).then(function() {
                waitAnimationEnd(rloader, function() {
                    rloader.classList.add("bounceIn");
                }).then(function(){
                    rloader.style.opacity = 1;
                });
            });
        };
        
    } else { 
        document.body.classList.remove("lock"); 
    } 

    
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
    
    //move...
    waitTransitionEnd(target, function() {
        target.style.transform = "translateX(-" + String(targetIndex * 100) + "%)";
    }).then(followUp);
}
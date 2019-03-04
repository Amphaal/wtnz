///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {

    //instantiation
    navigatorSpecificParameterization();
    bindResizeFunctions();
    instShoutMuteButton();
    registerXNavigateSwipeEvents();
    generateSortButtons();

    //download user file
    requestUserUnifiedLib();
});

function navigatorSpecificParameterization() {
    if (history && history['scrollRestoration'] !== undefined) {
        history.scrollRestoration = 'manual';
    }
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
    document.removeEventListener("scroll");
    inBetweenPromise().then(function() {
        document.addEventListener("scroll", onScroll);
    });
}

function onScroll(ev) {
    if(Math.abs(checkScrollSpeed()) < 10) return;
    headerToggle();
}

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(resizeFeed);
    resizeFunctions.width.push(resizeShout);
    Object.keys(_discoverFilter).forEach(function(id) {
        resizeFunctions.any.push(applyManualSizesFilterUIs(id));
    })
}

//download library
function requestUserUnifiedLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {
        return processUnifiedLib(e.currentTarget.responseText);
    };
    request.open('GET', clientURLUnified, true);
    request.send(null);
}

//process...
function processUnifiedLib(unifiedAsJSONText) {
    
    unified = JSON.parse(unifiedAsJSONText); //parse
    _appDataFeeds = generateDataFeeds(unified); //bind lib to data functions
    
    //stats rendering
    renderStats(unified); 

    //prepare UI
    prepareFilterUIs(Object.keys(_discoverFilter));
    applyCompareDateBulk();

    //instantiate initial filterUI
    updateFilterUIs(['genreUI']);
    
    //generate feed
    generateFreshUploads(); 
    
    //finally, display the app to the user
    displayApp().then(requestShout);
}
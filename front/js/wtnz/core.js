///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {

    //instantiation
    navigatorSpecificParameterization();
    bindResizeFunctions();
    scrollUiEventHandling();
    instShoutMuteButton();
    registerSwipeEvents();

    //download user file
    requestUserUnifiedLib();
});

function navigatorSpecificParameterization() {
    if (history && history['scrollRestoration'] !== undefined) {
        history.scrollRestoration = 'manual';
    }
}

function registerSwipeEvents() {
    var hammertime = new Hammer(document.body);
    hammertime.get('swipe').set({ direction: Hammer.DIRECTION_HORIZONTAL });
    hammertime.on('swipe', function(ev) {
        vNavigate(ev.direction);
    });
}

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(resizeFeed(document.getElementById('showFeed')));
    resizeFunctions.width.push(resizeShout());
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
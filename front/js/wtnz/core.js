///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {

    if (history && history['scrollRestoration'] !== undefined) {
        history.scrollRestoration = 'manual';
    }

    bindResizeFunctions();
    scrollUiEventHandling();
    instShoutMuteButton();
    requestUserLib();
});


//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(resizeFeed(document.getElementById('showFeed')));
    resizeFunctions.width.push(resizeShout());
    Object.keys(filter).forEach(function(id) {
        resizeFunctions.any.push(applyManualSizesFilterUIs(id));
    })
}

//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {
        return processLib(e.currentTarget.responseText);
    };
    request.open('GET', clientURLLibrary, true);
    request.send(null);
}

//process...
function processLib(libAsJSONText) {
    
    lib = JSON.parse(libAsJSONText); //parse
    dataFeeds = generateDataFeeds(lib); //bind lib to data functions
    
    //stats rendering
    renderStats(lib); 

    //prepare UI
    prepareFilterUIs(Object.keys(filter));
    applyCompareDateBulk();

    //instantiate initial filterUI
    updateFilterUIs(['genreUI']);
    
    //generate feed
    generateFreshUploads(); 

    //finally, display the app to the user
    displayApp().then(function(){
        requestShout();
    });
}
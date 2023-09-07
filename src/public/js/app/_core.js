///
/// STARTUP
///
window.addEventListener("load", function() {
    waitTransitionEnd(document.getElementById("loader-container"), function(elem) {
        elem.style.opacity = 1;
    }).then(initializateApp); 
});

function initializateApp() {
    
    //instantiation
    navigatorSpecificParameterization();
    alignConnectSideElements();
    bindResizeFunctions();
    instShoutMuteButton();
    registerXNavigateSwipeEvents();
    generateSortButtons();
    initSearchBand();
    _rLoader = new RLoader("xmlRLoader", initialRLoaderUrl);

    //download user file
    requestUserUnifiedLib();
}

//download library
function requestUserUnifiedLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {
        processUnifiedLib(e.currentTarget.responseText);
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

    //instantiate initial filterUI
    updateFilterUIs(['genreUI']);
    
    //generate feed
    generateFreshUploads(); 
    
    //finally, display the app to the user
    displayApp().then(requestShout);
}
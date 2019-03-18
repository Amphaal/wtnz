///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {

    //instantiation
    navigatorSpecificParameterization();
    alignConnectSideElements();
    bindResizeFunctions();
    instShoutMuteButton();
    registerXNavigateSwipeEvents();
    generateSortButtons();
    initSearchBand();

    //download user file
    requestUserUnifiedLib();
});

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
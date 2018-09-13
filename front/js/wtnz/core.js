///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {
    bindResizeFunctions();
    requestUserLib();
});

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(resizeFeed(document.getElementById('showFeed')));
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

    //finally, display the app to the user
    displayApp();
}
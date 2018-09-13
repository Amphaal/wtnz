///
/// STARTUP
///
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});

//download library
function requestUserLib() {
    let request = new XMLHttpRequest(); 
    request.onprogress = updateProgress;
    request.onloadend = function(e) {
        return processLib(e.target.responseText);
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
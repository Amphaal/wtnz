///
///ENTRYPOINT
///

function displayApp() {
    return new Promise(function(resolve){
        //end loading, start animations...
        hideLoader().then(function() {
            showApp().then(function() {
                removeLoader();
                resolve();
            });
        });
    });
}

///
///HELPERS UI
///

//hide loader bar
function hideLoader() {
    return new Promise(function(resolve, reject) {
        let loader = document.getElementById("loader");
        loader.classList.remove("fadeIn");
        loader.classList.add("fadeOut");

        return loader.addEventListener(whichAnimationEvent(), function lele(e) {
            loader.removeEventListener(whichAnimationEvent(), lele, false);
            resolve();
        }, false);
    });
}

//remove loader from layout
function removeLoader() {
    let target = document.getElementById("loader-container");
    target.parentElement.removeChild(target);
}

//show content
function showApp() {
    return new Promise(function(resolve) {
        let content = document.getElementById("wtnz");
        content.classList.add("animated");
        content.classList.add("fadeIn");

        return content.addEventListener(whichAnimationEvent(), function lele2(e) {
            content.removeEventListener(whichAnimationEvent(), lele2, false);
            resolve();
        }, false);
    });
}

//update loader bar
function updateProgress(evt){
    var total = evt.srcElement.getResponseHeader("x-original-content-length") || evt.total; //bypass chrome compression proxy
    if (total) {
        let percentComplete = (evt.loaded / total) * 100 - 100;  
        document.getElementById("loader-bar").style.transform = "translateX(" + percentComplete + "%)";
    } 
}

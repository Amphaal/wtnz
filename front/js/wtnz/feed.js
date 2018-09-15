function toggleFeed(event) {
    //resize the feed
    if(resizeFeed(event.currentTarget)()) {
        //if expended, wait for the animation to end to scroll
        let feedContainer = document.getElementById('feedContainer');
        feedContainer.addEventListener(whichTransitionEndEvent(), function ecee(e) {
            feedContainer.removeEventListener(whichTransitionEndEvent(), ecee, false);
            wtnzScroll(feedContainer);
        }, false);
    }
}

///
/// resize functions
///

function resizeFeed(checkboxElem) {
    return function() {
        let feedContainer = document.getElementById('feedContainer');
        let heightSwitch = checkboxElem.checked ? feedContainer.scrollHeight + "px" : "0";
        feedContainer.style.maxHeight = heightSwitch;
        return heightSwitch;
    }
}
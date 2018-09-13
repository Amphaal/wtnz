function toggleFeed(event) {
    resizeFeed(event.currentTarget)();
}

///
/// resize functions
///

function resizeFeed(checkboxElem) {
    return function() {
        let feedContainer = document.getElementById('feedContainer');
        let heightSwitch = checkboxElem.checked ? feedContainer.scrollHeight + "px" : "0";
        feedContainer.style.maxHeight = heightSwitch;
    }
}
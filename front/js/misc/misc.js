function removeNotification(selector) {
    document.querySelectorAll(selector).forEach(function(elem) {
        waitAnimationEnd(elem, function() {
            elem.classList.add("notifOut");
        }).then(function() {
            elem.classList.remove("notif");
            elem.classList.remove("notifOut");
        });
    });
}

function alignConnectSideElements() {
    let topMost = [];
    document.querySelectorAll("#wtnz-library .connect-side").forEach(function(e) {
        topMost.push(e.getBoundingClientRect().top);
    });
    topMost = Math.max(...topMost);

    let target = document.querySelector("#wtnz-connect .connect-side")
    target.style.top = topMost + "px";
}

function linkToYoutube(artist, albumOrTitle) {
    //link to YT
    let yt_query = 'https://www.youtube.com/results?search_query=';
    let album_query =  encodeURIComponent(artist+ ' ' + albumOrTitle)
                            .replace('  ', ' ')
                            .replace(' ', '+')
                            .toLowerCase();
    return yt_query + album_query;
}

function navigatorSpecificParameterization() {
    if (history && history['scrollRestoration'] !== undefined) {
        history.scrollRestoration = 'manual';
    }
}
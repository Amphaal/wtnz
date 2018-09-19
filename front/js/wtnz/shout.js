//download library
function requestShout() {
    let request = new XMLHttpRequest(); 
    request.onloadend = function(e) {
        let text = e.currentTarget.responseText;
        let shoutData = (!text.length) ? {} : JSON.parse(text);
        shout = shoutData;
        return displayShout(shoutData);
    };
    request.open('GET', clientURLShout, true);
    request.send();
}


function calculateSecondsElapsed(dateFrom) {
    let dateNow = new moment();
    let dateThen = moment(dateFrom);
    return moment.duration(dateNow.diff(dateThen)).asSeconds();
}

//check if worth displaying
function isWorthDisplayingShout(shoutData) {
        
        if ((!shoutData['duration'] || !shoutData['date'])) return 0; //if is no data
        if (!shoutData['playerState']) return 1; // if is paused

        //if is playing and remaning time comparing dates
        let remaining = (shoutData['duration'] || 0) - (shoutData['playerPosition'] || 0);
        let secondsElapsed = calculateSecondsElapsed(shoutData['date']);
        return (remaining - secondsElapsed) > 0;
    }

function notificateShout() {
    let shout = document.querySelector('#shoutContainer .shout');
    if(!isVisible(shout)) {
        alert('CACA');
    }
}

function displayShout(shoutData) {
    
    //prepare
    let aImage = document.querySelector('#shoutContainer .cover');
    let aLink = document.querySelector('#shoutContainer a');
    let aDescr = document.querySelector('#shoutContainer .albumDesc .name');
    let aMeta = document.querySelector('#shoutContainer .albumDesc .meta');
    let aTimeline = document.querySelector('#shoutContainer .timeline');

    //reset values
    resetImgLoader(aImage);
    aLink.removeAttribute('href');
    aDescr.innerHTML = '';
    aMeta.innerHTML = '';
    aTimeline.style.animationDuration = null;
    aTimeline.style.animationDelay = null;
    aTimeline.style.animationPlayState = null;

    //check if worth
    if (isWorthDisplayingShout(shoutData)) {

        //prepare data
        let artist = shoutData['artist'];
        let name = shoutData['name'];
        let album = shoutData['album'];
        let duration = shoutData['duration'];
        let state = shoutData['playerState'];

        //update cover
        queryMusicBrainzForAlbumCover('shout', album, artist).then(
            function(imgUrl) {
                updateImgLoader(aImage, imgUrl);
            },function() {
                brokenImgFr(aImage);
            }
        );

        //update link
        aLink.setAttribute('href', linkToYoutube(artist, name));

        //album descr
        aDescr.innerHTML = name;
        aMeta.innerHTML = [artist, album].join(" - ");

        //progress bar
        aTimeline.style.animationDuration = duration + 's';
        let position = shoutData['playerPosition'] + (state ? calculateSecondsElapsed(shoutData['date']) : 0);
        aTimeline.style.animationDelay = -position + 's';
        if(!state) aTimeline.style.animationPlayState = 'paused';
    }

    //display/hide
    resizeShout()();
    notificateShout();
}

///
/// resize functions
///

function resizeShout() {
    return function() {
        let shoutContainer = document.getElementById('shoutContainer');
        let heightSwitch = isWorthDisplayingShout(shout) ? shoutContainer.scrollHeight + "px" : "0";
        shoutContainer.style.maxHeight = heightSwitch;
        return heightSwitch;
    }
}

/** @type {WebSocket | null} */
var socket = null;

// download library
function requestShout() {
    //
    const socketServerUrl = "ws" + (location.protocol === 'https:' ? 's' : '') + '://' + sioURL + "/" + libraryUser + "/shout";
    socket = new WebSocket(socketServerUrl);
    console.log("Initilization of WebSocket on", socketServerUrl, "...");

    //
    socket.addEventListener("message", (event) => {
        const payload = JSON.parse(event.data);
        switch(payload.id) {
            case "newShout": {
                onReceivedShout(payload.r);
            }
            break;
        }
    });

    //
    socket.addEventListener("open", () => {
        console.log("Web socket opened !");
    });

    socket.addEventListener("close", () => {
        console.log("Web socket closed...");
    });

    socket.addEventListener("error", () => {
        console.log("Web socket failed.");
    });
}


//check if worth displaying
function isWorthDisplayingShout(shoutData) {
    if ((!shoutData['duration'] || !shoutData['date'])) return 0; //if is no data
    if (!shoutData['playerState']) return 1; // if is paused

    //if is playing and remaning time comparing dates
    let remaining = (shoutData['duration'] || 0) - (shoutData['playerPosition'] || 0);
    let secondsElapsed = calculateSecondsElapsed(shoutData['date']);
    let isWorth = (remaining - secondsElapsed) > 0;
    return isWorth;
}

//sound handling
var notificationShoutSound = null;
var msnStorageKey = 'muteShoutNotification';
function instShoutMuteButton() {
    
    //prepare
    let mustMute = localStorage.getItem(msnStorageKey);
    let icon = document.querySelector('#shoutContainer .mute i');
    
    //instantiate sound
    if(notificationShoutSound == null) {
        notificationShoutSound = new Audio('/public/audio/long-expected.mp3');
        notificationShoutSound.autoplay = false;
        notificationShoutSound.muted = false;
    }

    //generate on state change
    if (mustMute == "1") {
        notificationShoutSound.volume = 0;
        icon.classList.remove('fa-bell');
        icon.classList.add('fa-bell-slash');
        icon.setAttribute('title', icon.getAttribute("title-on"));
    } else {
        notificationShoutSound.volume = .05;
        icon.classList.remove('fa-bell-slash');
        icon.classList.add('fa-bell');
        icon.setAttribute('title', icon.getAttribute("title-off"));
    }
}

//click toogle from UI
function toggleShoutSound(event) {
    let mustMute = localStorage.getItem(msnStorageKey);
    localStorage.setItem(msnStorageKey, mustMute == "1" ? "0" : "1");
    instShoutMuteButton();
}


function _isInClientViewField(elem) {
    let boundaries = elem.getBoundingClientRect();
    return boundaries.bottom >= 0 && boundaries.left >= 0;
}

//display shout
function onReceivedShout(newShoutData) {

    //update current shout
    _currentShoutDWorth = isWorthDisplayingShout(newShoutData);

    //check what kind of update to apply
    let isNoMusicShout = newShoutData['date'] && Object.keys(newShoutData).length == 1;
    let changes = compareShoutChanges(newShoutData);
    let isHardChange = !isNoMusicShout && (
        changes.includes('artist') || changes.includes('album') || changes.includes('name')
    );

    
    //preapre
    let shoutContainer = document.getElementById('shoutContainer');
    let notif = document.getElementById('shoutNotification');

    //anticipate next action
    let afterNotificationPanelShown = function() {
        return new Promise(function(resolve) {
            toggleShout().then(function() {

                _updateShoutDisplayableData(newShoutData, changes);
        
                //update values
                _currentShout = newShoutData;

                //notif if scrolled
                if(!_isInClientViewField(shoutContainer)) {
        
                    //force refresh anim
                    let out = document.getElementById('shoutNotificationWidget');
                    window.requestAnimationFrame(function() {
                        out.classList.remove('show');
                        void out.offsetWidth;
                        out.classList.add('show');
                    });
                }
                //display main notif frame
                if(isHardChange) window.requestAnimationFrame(function() {
                    
                    //play sound
                    notificationShoutSound.play().then(null, function(e) {
                        /* expected on Chrome */
                    });
                    
                    //display
                    notif.classList.add('fade');
                });

                resolve();
            });
        });
    }

    //if shouts are already kicking in > trigger notif before re-toggling
    let isShoutContainerRefreshingContent = shoutContainer.clientHeight;
    if(isShoutContainerRefreshingContent && notif.classList.contains('fade') && isHardChange) {

        waitTransitionEnd(notif, function() {
            notif.classList.remove('fade');
        }).then(afterNotificationPanelShown());

    } else {

        afterNotificationPanelShown();

    }

}

//list changes between states
function compareShoutChanges(newShout) {

    a = Object.keys(_currentShout);
    b = Object.keys(newShout);
    c = new Set(a.concat(b));
    d = [];
    c.forEach(function(v){d.push(v);});
    return d.filter(function(id){
        return newShout[id] !== _currentShout[id];
    });
}


function _updateShoutDisplayableData(shoutData, changes) {

    //prepare data helpers
    let artist = shoutData['artist'];
    let name = shoutData['name'];
    let album = shoutData['album'];
    let genre = shoutData['genre'];
    let year = shoutData['year'];
    let duration = shoutData['duration'];
    let state = shoutData['playerState'];

    //update image
    if (changes.includes('album')) {
        let aImage = document.querySelector('#shoutContainer .cover');
        resetImgLoader(aImage);
        if(album && artist) queryMusicBrainzForAlbumCover('shout', album, artist).then(
            function(imgUrl) {
                updateImgLoader(aImage, imgUrl);
            },function() {
                brokenImgFr(aImage);
            }
        );
    }

    //update link
    if (changes.includes('artist') || changes.includes('name')) {
        let aLink = document.querySelector('#shoutContainer a');
        aLink.removeAttribute('href');
        aLink.removeAttribute('target');
        if(artist && name) {
            aLink.setAttribute('href', linkToYoutube(artist, name));
            aLink.setAttribute('target','_blank');
        }
    }

    //update track name
    if (changes.includes('name')) {
        let aDescr = document.querySelector('#shoutContainer .albumDesc .name');
        aDescr.innerHTML = '';
        if(name) aDescr.innerHTML = name;
    }

    //update meta 
    if (changes.includes('artist') || changes.includes('album')) {
        let aMeta = document.querySelector('#shoutContainer .albumDesc .meta');
        aMeta.innerHTML = '';
        if(artist && album) aMeta.innerHTML = [artist, album].join(" - ");
        if(year) aMeta.innerHTML += " (" + year + ")";
        if(genre) {
            aMeta.innerHTML = "<span>" + aMeta.innerHTML + "</span>";
            aMeta.innerHTML += "<span style='color:grey'>&nbsp;//&nbsp;" + genre + "</span>";
        }
    }

    //update timeline
    if(changes.includes('duration') || changes.includes('playerPosition') || changes.includes('playerState') || changes.includes('date')) {
        let aTimeline = document.querySelector('#shoutContainer .timeline');
        
        //reset animation
        aTimeline.style.animationDuration = null;
        aTimeline.style.animationDelay = null;
        aTimeline.style.animationPlayState = null;
        aTimeline.classList.remove('animTimeline');

        //progress bar
        void aTimeline.offsetWidth;
        let position = shoutData['playerPosition'] + (state ? calculateSecondsElapsed(shoutData['date']) : 0);
        aTimeline.style.animationDuration = duration + 's';
        aTimeline.style.animationDelay = -position + 's';
        if(!state) aTimeline.style.animationPlayState = 'paused';
        aTimeline.classList.add('animTimeline');      
    }

}

///
/// resize functions
///

function resizeShout() {
    return _resizeShutter(
        'shoutContainer', 
        _currentShoutDWorth
    );
}

function toggleShout() {
    return _toggleShutter('shoutContainer', resizeShout);
}
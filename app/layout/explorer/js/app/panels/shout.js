/** @type {WebSocket} */
var socket = null;
var hbEmitter = null;

/**
 * 
 * @param {WebSocket} socket 
 */
function doWSPing(socket) {
    console.log("Ping...");
    socket?.send(JSON.stringify({id: 'ping', r: ''}));
}

//
function createWebSocketForShouts() {
    //
    const socketServerUrl = "ws" + (location.protocol === 'https:' ? 's' : '') + '://' + sioURL + "/" + libraryUser + "/shout";
    socket = new WebSocket(socketServerUrl);
    console.log("Initialization of WebSockets client on", socketServerUrl, "...");

    /**
     * 
     * @param {MessageEvent} event 
     */
    const onMessage = (event) => {
        const payload = JSON.parse(event.data);
        switch(payload.id) {
            //
            case "newShout": {
                console.log('Received shout update ! Handling...');
                onReceivedShout(JSON.parse(payload.r));
            }
            break;

            //
            case "pong": {
                console.log('...Pong !');
            }
            break;
        }
    };

    /** */
    const onOpen = () => {
        //
        doWSPing(socket);

        //
        hbEmitter = setInterval(() => {
            doWSPing(socket);
        }, 30000);

        //
        console.log("Web socket opened !");
    };

    /** */
    const onError = () => {
        console.log("Web socket failed.");
    };

    /** */
    const onClose = () => {
        if (hbEmitter != null) {
            clearInterval(hbEmitter);
            hbEmitter = null;
        }

        console.log("Web socket closed...");

        //
        //
        //

        socket.removeEventListener("message", onMessage);
        socket.removeEventListener("open", onOpen);
        socket.removeEventListener("error", onError);
        socket.removeEventListener("close", onClose);

        // reconnect after 1s
        setTimeout(createWebSocketForShouts, 1000);
    };

    //
    socket.addEventListener("message", onMessage);
    socket.addEventListener("open", onOpen);
    socket.addEventListener("error", onError);
    socket.addEventListener("close", onClose);

}


/**
 * Depending on shout timestamp, determines if it is meaningful or not to update any informations on UI relative to its update
 * @param {object} shoutData 
 * @returns {boolean}
 */
function isWorthDisplayingShout(shoutData) {
    if (typeof(shoutData['duration']) !== 'number' || typeof(shoutData['date']) !== 'string') return false; // if is no correct data about track length or current timestamp, aint no worth
    if (!shoutData['playerState']) return true; // if is paused, always meaningful

    //
    // so then, if it is playing and remaning time comparing dates
    //

    /** @type {number} */
    const effectiveDuration = shoutData['duration'] ?? 0;
    /** @type {number} */
    const effectivePlayerPosition = shoutData['playerPosition'] ?? 0;

    //
    const remainingSecondsBeforeTrackPlayEnds = Math.max(effectiveDuration - effectivePlayerPosition, 0);

    /** @type {string} UTC+0 ISO timestamp */
    const shoutTs = shoutData['date'];
    const secondsElapsedSinceLatestShoutUpdate = calculateSecondsElapsed(shoutTs);
    const isWorthDisplaying = (remainingSecondsBeforeTrackPlayEnds - secondsElapsedSinceLatestShoutUpdate) > 0;

    //
    return isWorthDisplaying;
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

/**
 * will handle the way to display new shout data
 * @param {object} newShoutData 
 */
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
                    if (navigator.userActivation && navigator.userActivation.hasBeenActive) {
                        notificationShoutSound.play().then(null, function(_) {
                            /* expected on Chrome */
                        });
                    }
                    
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
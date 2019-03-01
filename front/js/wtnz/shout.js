//download library
function requestShout() {
    let socketServer = sioURL + "/shout";
    var socket = io(socketServer, {
        query : { 
            userToWatch : libraryUser 
        }
    });
    socket.on("newShout", function(newShout) {
        newShout = JSON.parse(newShout);
        displayShout(newShout);
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
        notificationShoutSound = new Audio('front/sound/long-expected.mp3');
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

//notification on music change
var lastNotifShoutId = '';
function notificateShout() {
    //preapre
    let shoutElem = document.getElementById('shoutContainer');
    let notifShoutId = [_currentShout.name, _currentShout.album, _currentShout.artist].join('_');
    let isShoutContainerVisible = isVisible(shoutElem);
    let isShoutDisplayed = shoutElem.clientHeight;
    let isNewShout = notifShoutId !== lastNotifShoutId;

    if(isShoutDisplayed && isNewShout) {
        //prepare
        let notif = document.getElementById('shoutNotification');

        let fadeAnim = function() {
            notif.classList.add('fade');
        };
        

        //animation
        let fd = function() {
            setTimeout(fadeAnim, 1000);
        } 

        //animation
        if(notif.classList.contains('fade')) {
            notif.classList.remove('fade');
            waitTransitionEnd(notif).then(fd);
        } else {
            fd();
        }

        //notif if scrolled
        if(!isShoutContainerVisible) {

            //force refresh anim
            let out = document.getElementById('shoutNotificationWidget');
            out.classList.remove('show');
            void out.offsetWidth;
            out.classList.add('show');
        }

        //play sound
        if(notificationShoutSound) {
            notificationShoutSound.play().then(null, function(e) {
                /* expected on Chrome */
            });
        }

        //update shoutid
        lastNotifShoutId = notifShoutId;
    }


}

//display shout
function displayShout(shoutData) {
    
    //check if must refresh
    let isWorth = isWorthDisplayingShout(shoutData);
    if (isWorth) {

        //list changes between states
        a = Object.keys(_currentShout);
        b = Object.keys(shoutData);
        c = new Set(a.concat(b));
        d = [];
        c.forEach(function(v){d.push(v);});
        let changes = d.filter(function(id){
            return shoutData[id] !== _currentShout[id];
        });

        //prepare data helpers
        let artist = shoutData['artist'];
        let name = shoutData['name'];
        let album = shoutData['album'];
        let duration = shoutData['duration'];
        let state = shoutData['playerState'];
        let genre = shoutData['genre'];
        let year = shoutData['year'];

        //update shout loader
        if (changes.includes('album') || changes.includes('artist') || changes.includes('name')) {
            let aNotif = document.getElementById('shoutNotification');
            aNotif.classList.remove('fade');
        }

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

    //update current shout
    _currentShout = shoutData;

    //display/hide
    resizeShout()().then(notificateShout);
}

///
/// resize functions
///

function resizeShout() {
    return function() {
            return new Promise(function(resolve) {
        
            let shoutContainer = document.getElementById('shoutContainer');
            let isWorth = isWorthDisplayingShout(_currentShout);
            let heightSwitch = isWorth ? shoutContainer.scrollHeight + "px" : "";
            let animOpen = shoutContainer.style.maxHeight === "";
            shoutContainer.style.maxHeight = heightSwitch;
            let resolving = function() {resolve(heightSwitch);}

            if (animOpen) {
                waitTransitionEnd(shoutContainer).then(resolving);
            } else {
                resolving();
            }
            
        });
    }
}

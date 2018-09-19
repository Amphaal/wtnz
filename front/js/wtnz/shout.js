//download library
var shoutAsString = "";
function requestShout() {
    let request = new XMLHttpRequest(); 
    request.onloadend = function(e) {
        let text = e.currentTarget.responseText;
        if(shoutAsString !== text) {
            shoutAsString = text;
            let newShout = (!text.length) ? {} : JSON.parse(text);
            displayShout(newShout);
        }
        setTimeout(requestShout, 1000);
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

var notificationShoutSound = new Audio('front/sound/long-expected.mp3');
notificationShoutSound.volume = .05;
var lastNotifShoutId = '';
function notificateShout() {
    //preapre
    let shoutElem = document.querySelector('#shoutContainer .shout');
    let notifShoutId = [shout.name, shout.album, shout.artist].join('_');
    let isShoutContainerVisible = isVisible(shoutElem);
    let isShoutDisplayed = shoutElem.scrollHeight;
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
            notif.addEventListener(whichTransitionEndEvent(), function lelel(e) {
                notif.removeEventListener(whichTransitionEndEvent(), lelel, false);
                fd();           
            }, false);
        } else {
            fd();
        }

        //notif if scrolled
        if(!isShoutContainerVisible) {

            //force refresh anim
            let out = document.getElementById('shoutNotificationOut');
            out.classList.remove('show');
            void out.offsetWidth;
            out.classList.add('show');
        }

        //play sound
        notificationShoutSound.play();

        //update shoutid
        lastNotifShoutId = notifShoutId;
    }


}

function displayShout(shoutData) {
    
    //list changes between states
    a = Object.keys(shout);
    b = Object.keys(shoutData);
    c = new Set(a.concat(b));
    d = [];
    c.forEach(v => d.push(v));
    let changes = d.filter(function(id){
        return shoutData[id] !== shout[id];
    });

    //prepare data helpers
    let artist = shoutData['artist'];
    let name = shoutData['name'];
    let album = shoutData['album'];
    let duration = shoutData['duration'];
    let state = shoutData['playerState'];


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
        if(artist && name) aLink.setAttribute('href', linkToYoutube(artist, name));
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
    }

    //update timeline
    if(changes.includes('duration') || changes.includes('playerPosition') || changes.includes('playerState')) {
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

    shout = shoutData;

    //display/hide
    resizeShout()().then(function() {
        notificateShout();
    });
}

///
/// resize functions
///

function resizeShout() {
    return function() {
            return new Promise(function(resolve) {
        
            let shoutContainer = document.getElementById('shoutContainer');
            let isWorth = isWorthDisplayingShout(shout);
            let heightSwitch = isWorth ? shoutContainer.scrollHeight + "px" : "0px";
            let animOpen = shoutContainer.style.maxHeight === "0px";
            shoutContainer.style.maxHeight = heightSwitch;

            let resolving = function() {resolve(heightSwitch);}

            if (animOpen) {
                shoutContainer.addEventListener(whichTransitionEndEvent(), function ww(e) {
                    shoutContainer.removeEventListener(whichTransitionEndEvent(), ww, false);
                    resolving();        
                }, false);
            } else {
                resolving();
            }
            
        });
    }
}

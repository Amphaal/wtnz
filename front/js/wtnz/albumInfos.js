//handle album infos rendering
function displayAlbumInfos(dataFunc) {
    return new Promise(function(resolve) {

        let data = dataFunc(); 

        let target = document.getElementById('albumInfos');
        
        let aYear = document.getElementById('aYear');
        let aGenre = document.getElementById('aGenre');
        let aDateAdded = document.getElementById('aDateAdded');
        let aTracks = document.getElementById('aTracks');
        let aTitle = document.getElementById('aTitle');

        //purge
        [aYear, aGenre, aDateAdded, aTracks].forEach(function(e) {
                e.innerHTML = '';
                e.removeAttribute('title');
        });
    
        //specific image purge
        let aImage = document.getElementById('aImage');
        aImage.firstChild.removeAttribute('src');
        aImage.classList.remove('noImgFound');
        aImage.classList.add('searchingCover');
    
        //clean link to YT
        let link = document.querySelector('#albumInfos .listen a');
        link.setAttribute('href', null);

        let bypass = false;
        if(target.classList.contains("show")) {
            target.classList.remove('show');
            bypass = true;
        }
    
        //then fill
        if(data) {
    
            //specific async image handler
            aImage.classList.add('searchingCover');
            queryMusicBrainzForAlbumCover().then(function(imgUrl) {
                aImage.classList.remove('searchingCover');
                aImage.firstChild.setAttribute('src', imgUrl);
            }, function() {
                brokenImgFr(aImage.firstChild, aImage);
            });
    
            aYear.innerHTML = data['Year'];
            aGenre.innerHTML = data['Genre'];
            aTitle.innerHTML = data['Album'];
    
            aDateAdded.innerHTML = compareDateFromNomHumanized(data['DateAdded']);
            aDateAdded.setAttribute('title', data['DateAdded']);
            
            Object.keys(data['Tracks']).forEach(function(trackId) {
                aTracks.innerHTML += '<li>' + data['Tracks'][trackId] + '</li>';
            });

            //link to YT
            let yt_query = 'https://www.youtube.com/results?search_query=';
            let album_query =  (data['Artist'] + ' ' + data['Album']).replace('  ', ' ').replace(' ', '+').toLowerCase();
            link.setAttribute('href', yt_query + album_query);

            //should wait for animation to end
            if (!bypass) {
                target.addEventListener(whichTransitionEndEvent(), function scd(e) {
                    target.removeEventListener(whichTransitionEndEvent(), scd, false);
                    resolve(target);
                }, false);
            } else {
                resolve(target);
            }

            //show, start animation
            target.classList.add('show');

        }
    });
}

function brokenImgFr(eImg, eContainer) {
    eContainer.classList.remove('searchingCover');
    eContainer.classList.add('noImgFound');
    eImg.removeAttribute('src');
}

function brokenImg(event) {
    brokenImgFr(event.currentTarget, event.currentTarget.parentElement);
}
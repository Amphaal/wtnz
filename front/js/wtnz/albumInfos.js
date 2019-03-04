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
        resetImgLoader(aImage);
    
        //clean link to YT
        let link = document.querySelector('#albumInfos .listen a');
        link.setAttribute('href', null);
        link.setAttribute('target', null);

        let bypass = false;
        if(target.classList.contains("show")) {
            target.classList.remove('show');
            bypass = true;
        }
    
        //then fill
        if(data) {
    
            //specific async image handler
            aImage.classList.add('searchingCover');
            queryMusicBrainzForAlbumCover().then(
                function(imgUrl) {
                    updateImgLoader(aImage, imgUrl);
                }, function() {
                    brokenImgFr(aImage);
                }
            );
    
            aYear.innerHTML = data['Year'];
            aGenre.innerHTML = data['Genre'];
            aTitle.innerHTML = data['Album'];
    
            aDateAdded.innerHTML = compareDateFromNomHumanized(data['DateAdded']);
            aDateAdded.setAttribute('title', data['DateAdded']);
            
            Object.keys(data['Tracks']).forEach(function(trackId) {
                aTracks.innerHTML += '<li>' + data['Tracks'][trackId] + '</li>';
            });

            //link to YT
            link.setAttribute('href', linkToYoutube(data['Artist'], data['Album']));
            link.setAttribute('target', "_blank");

            //show, start animation
            let action = function() {target.classList.add('show');};

            //should wait for animation to end
            if (!bypass) {
                waitTransitionEnd(target, action).then(resolve);
            } else {
                action();
                resolve(target);
            }
        }
    });
}

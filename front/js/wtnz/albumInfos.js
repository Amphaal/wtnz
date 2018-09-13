//handle album infos rendering
function displayAlbumInfos(dataFunc) {
    
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

    target.classList.remove('show');

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
        
        target.classList.add('show');
    }
}

function brokenImgFr(eImg, eContainer) {
    eContainer.classList.remove('searchingCover');
    eContainer.classList.add('noImgFound');
    eImg.removeAttribute('src');
}

function brokenImg(event) {
    brokenImgFr(event.target, event.target.parentElement);
}
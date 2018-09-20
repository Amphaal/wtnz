function toggleFeed(event) {
    //resize the feed
    if(resizeFeed(event.currentTarget)()) {
        //if expended, wait for the animation to end to scroll
        let feedContainer = document.getElementById('feedContainer');
        feedContainer.addEventListener(whichTransitionEndEvent(), function ecee(e) {
            feedContainer.removeEventListener(whichTransitionEndEvent(), ecee, false);
            wtnzScroll(feedContainer);
        }, false);
    }
}

///
/// resize functions
///

function resizeFeed(checkboxElem) {
    return function() {
        let feedContainer = document.getElementById('feedContainer');
        let heightSwitch = checkboxElem.checked ? feedContainer.scrollHeight + "px" : "0";
        feedContainer.style.maxHeight = heightSwitch;
        return heightSwitch;
    }
}

function generateFreshUploads() {
    let data = dataFeeds.feedUploads()
    let target = document.querySelector('#feedContainer .feedWrapper');

    //for each interval
    Object.keys(data).forEach(function(interval) {
        
        //prepare
        let section = document.createElement('section');
        let table = document.createElement('table');
        table.classList.add('sortable');
        let title = document.createElement('h1');
        title.innerHTML = interval;

        //head
        let tHead = document.createElement('thead');
        let headerRow = document.createElement('tr');
        ['Year', 'Genre', 'Artist', 'Album'].forEach(function(head) {
            let thElem = document.createElement('th');
            thElem.innerHTML = head;
            headerRow.appendChild(thElem);
        });
        tHead.appendChild(headerRow);
        table.appendChild(tHead);

        // body / albums
        let tBody = document.createElement('tbody');
        data[interval].forEach(function(album) {

            let albumElem = document.createElement('tr');
            let cellVals = [album['Year'], album['Genre'], album['Artist'],album['Album']];
            
            cellVals.forEach(function(cellVal){
                let cellElem = document.createElement('td');
                cellElem.innerHTML = cellVal;
                albumElem.appendChild(cellElem);
            });
            
            tBody.appendChild(albumElem);

        });
        table.appendChild(tBody);

        section.appendChild(title);
        section.appendChild(table);

        sorttable.makeSortable(table);
        target.appendChild(section);
    });
}
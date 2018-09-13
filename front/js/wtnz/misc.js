function applyCompareDateBulk() {
    //update date to compared and humanized
    let dateElems = document.querySelectorAll('[data-date]');
    dateElems.forEach(function(dateElem) {
        let dateHum = compareDateFromNomHumanized(dateElem.dataset.date);
        dateElem.innerHTML = dateElem.innerHTML.replace('${date}', dateHum);
    });
}

function toggleFeed(event) {
    let feedContainer = document.getElementById('feedContainer');
    let heightSwitch = event.target.checked ? feedContainer.scrollHeight + "px" : "0";
    feedContainer.style.height = heightSwitch;
}
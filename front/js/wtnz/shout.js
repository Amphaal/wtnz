//download library
function requestShout() {
    let request = new XMLHttpRequest(); 
    request.onloadend = function(e) {
        let text = e.currentTarget.responseText;
        let shoutData = (!text.length) ? {} : JSON.parse(text);
        return displayShout(shoutData);
    };
    request.open('GET', clientURLShout, true);
    request.send(null);
}

function displayShout(shoutData) {

    //display/hide
    let shoutContainer = document.getElementById('shoutContainer');
    let heightSwitch = shoutData.name ? shoutContainer.scrollHeight + "px" : "0";
    shoutContainer.style.maxHeight = heightSwitch;
}
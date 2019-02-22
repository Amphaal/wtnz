function toggleProfile(event) {
    //resize the profile
    if(resizeProfile(event.currentTarget)()) {
        //if expended, wait for the animation to end to scroll
        let profileContainer = document.getElementById('profileContainer');
        profileContainer.addEventListener(whichTransitionEndEvent(), function addada(e) {
            profileContainer.removeEventListener(whichTransitionEndEvent(), addada, false);
        }, false);
    }
}

///
/// resize functions
///

function resizeProfile(checkboxElem) {
    return function() {
        let profileContainer = document.getElementById('profileContainer');
        let heightSwitch = checkboxElem.checked ? profileContainer.scrollHeight + "px" : "0";
        profileContainer.style.maxHeight = heightSwitch;
        return heightSwitch;
    }
}
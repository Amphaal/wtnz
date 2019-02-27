function toggleProfile(event) {
    //resize the profile
    resizeProfile(event.currentTarget)()
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
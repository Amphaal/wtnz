function toggleProfile(event) {
    resizeProfile();
}

///
/// resize functions
///

function resizeProfile() {
    return _resizeShutter(
        'profileContainer',
        document.getElementById('showProfile').checked
    );
}
<?php 

function routerMusicLibrary($user_qs) {

    $expectedLibrary = getInternalUserFolder($user_qs) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    $profilePicture = getProfilePicture($user_qs);
    
    $expectedProfilePic = NULL;
    if ($profilePicture) {
        $expectedProfilePic = getPublicUserFolder($user_qs) . $profilePicture;
    }

    $clientURLUnified = getPublicUserFolder($user_qs) . constant("COMPILED_MUSIC_LIB_PROFILE_FILE_NAME");
    $clientURLShout = getPublicUserFolder($user_qs) . constant("SHOUT_PROFILE_FILE_NAME");


    //Client variables
    $latestUpdate = filemtime($expectedLibrary);
    $isLogged = isUserLogged();

    //addons
    setTitle(i18n('libraryOf', $user_qs));
    $initialRLoaderUrl = getLocation("Home", true);

    include $_SERVER["DOCUMENT_ROOT"] . "/layout/explorer/entrypoint.php";
    exit;
}

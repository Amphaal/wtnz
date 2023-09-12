<?php 

function routerLibrary($user_qs) {

    $expectedLibrary = getInternalUserFolder($user_qs) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    $expectedProfilePic = getPublicUserFolder($user_qs) . getProfilePicture($user_qs);

    $clientURLUnified = getPublicUserFolder($user_qs) . constant("COMPILED_MUSIC_LIB_PROFILE_FILE_NAME");
    $clientURLShout = getPublicUserFolder($user_qs) . constant("SHOUT_PROFILE_FILE_NAME");

    //Client variables
    $latestUpdate = filemtime($expectedLibrary);
    $isLogged = isUserLogged();

    //addons
    setTitle(i18n('libraryOf', $user_qs));
    $initialRLoaderUrl = getLocation("Home", true);

    include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/explorer/entrypoint.php";
    exit;
}

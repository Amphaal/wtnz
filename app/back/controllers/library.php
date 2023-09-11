<?php 

function routerLibrary($user_qs) {

    $expectedLibrary = getInternalUserFolder($user_qs) . getCurrentLibraryFileName();
    $expectedProfilePic = getPublicUserFolder($user_qs) . getProfilePicture($user_qs);

    $clientURLUnified = getPublicUserFolder($user_qs) . getUnifiedLibraryFileName();
    $clientURLShout = getPublicUserFolder($user_qs) . getCurrentShoutFileName();

    //Client variables
    $latestUpdate = filemtime($expectedLibrary);
    $isLogged = isUserLogged();

    //addons
    setTitle(i18n('libraryOf', $user_qs));
    $initialRLoaderUrl = getLocation("Home", true);

    include $_SERVER['DOCUMENT_ROOT'] . "/app/front/front.php";
    exit;
}

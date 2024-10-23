<?php 

function routerInterceptor_MusicLibrary($request, $qs_user) {

    $expectedLibrary = getInternalUserFolder($qs_user) . constant("MUSIC_LIB_PROFILE_FILE_NAME");
    $profilePicture = getProfilePicture($qs_user);
    
    $expectedProfilePic = NULL;
    if ($profilePicture) {
        $expectedProfilePic = getPublicUserFolderOf($qs_user) . $profilePicture;
    }

    $clientURLUnified = getPublicUserFolderOf($qs_user) . constant("COMPILED_MUSIC_LIB_PROFILE_FILE_NAME");
    $clientURLShout = getPublicUserFolderOf($qs_user) . constant("SHOUT_PROFILE_FILE_NAME");

    //Client variables
    $latestUpdate = filemtime($expectedLibrary);
    $isLogged = isUserLogged();

    //addons
    setTitle($i18n('libraryOf', $qs_user));
    $initialRLoaderUrl = getLocation($request, "Home", true);

    include $documentRoot . "/layout/explorer/entrypoint.php";
    exit;
}

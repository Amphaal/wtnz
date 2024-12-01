<?php 

function routerInterceptor_MusicLibrary($qs_user) {

    $expectedLibrary = getInternalUserFolder($qs_user) . '/' . MUSIC_LIB_PROFILE_FILE_NAME;
    $profilePicture = getProfilePicture($qs_user);
    
    $expectedProfilePic = NULL;
    if ($profilePicture) {
        $expectedProfilePic = getPublicUserFolderOf($qs_user) . '/' . $profilePicture;
    }

    $clientURLUnified = getPublicUserFolderOf($qs_user) . '/' . COMPILED_MUSIC_LIB_PROFILE_FILE_NAME;
    $clientURLShout = getPublicUserFolderOf($qs_user) . '/' . SHOUT_PROFILE_FILE_NAME;

    //Client variables
    // if (file_exists($expectedLibrary)) {
    //     $latestUpdate = filemtime($expectedLibrary);
    // }
    
    $isLogged = isUserLogged();

    //addons
    ContextManager::get("set_title")(i18n('libraryOf', $qs_user));
    $initialRLoaderUrl = getLocation("Home", true);

    include SOURCE_PHP_ROOT . "/layout/explorer/js/vars.php";
    include SOURCE_PHP_ROOT . "/layout/explorer/entrypoint.php";
}

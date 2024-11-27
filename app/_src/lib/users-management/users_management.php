<?php

include SOURCE_PHP_ROOT . "/lib/users-management/db.php";

/** */
function checkUserSpecificFolders() {
    //for each user
    foreach(UserDb::all() as $user => $confData) {
        $path = getInternalUserFolder($user);
        _mayCreateUserDirectory($path);
    }
}  

function _mayCreateUserDirectory($directory) {
    $shouldWrite = !file_exists($directory);
    if (!$shouldWrite) return null;

    $result = mkdir($directory, 0777, true);
    if (!$result) 
    {
        errorOccured(i18n("e_wdu", $directory));
    }
}

function checkUserExists($user, bool $non_fatal_check) {
    $do_exist = UserDb::from($user) != null && file_exists(getInternalUserFolder($user));
    if(!$do_exist && !$non_fatal_check) errorOccured(i18n("e_unsu", $user));
    return $do_exist;
}

function checkPOSTedUserPassword($of_user) {
    $passwd = ContextManager::get("REQUEST")->post['password'];
    if(empty($passwd)) errorOccured(i18n("e_nopass"));
    if($passwd != UserDb::from($of_user)["password"]) errorOccured(i18n("e_pmm"));
}

function setMyProfilePicture($ppFilename) {
    UserDb::update([
        "profilePic" => $ppFilename
    ]);
}

function getProfilePicture($user) {
    $config = UserDb::from($user);
    if(!$config) return;
    if(!array_key_exists("profilePic", $config)) return;

    $profilePicPath = $config["profilePic"];
    return $profilePicPath;
}

//
// connectivity
//

/**
 * @return bool
 */
function isUserLogged() {
    return !empty(Session::getLoggedUser());
}

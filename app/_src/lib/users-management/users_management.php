<?php

include $sourcePhpRoot . "/lib/users-management/db.php";

/** */
function checkUserSpecificFolders(string $sourcePhpRoot, $request) {
    //for each user
    foreach(UserDb::all($sourcePhpRoot) as $user => $confData) {
        $path = getInternalUserFolder($sourcePhpRoot, $user);
        _mayCreateUserDirectory($request, $path);
    }
}  

function _mayCreateUserDirectory($request, $directory) {
    $shouldWrite = !file_exists($directory);
    if (!$shouldWrite) return null;

    $result = mkdir($directory, 0777, true);
    if (!$result) 
    {
        errorOccured($request, ContextManager::get("i18n")("e_wdu", $directory));
    }
}

function checkUserExists(string $sourcePhpRoot, $request, $user, $non_fatal_check = false) {
    $do_exist = UserDb::from($sourcePhpRoot, $user) != null && file_exists(getInternalUserFolder($sourcePhpRoot, $user));
    if(!$do_exist && !$non_fatal_check) errorOccured($request, ContextManager::get("i18n")("e_unsu", $user));
    return $do_exist;
}

function checkPOSTedUserPassword(string $sourcePhpRoot, $request, $of_user) {
    $passwd = isset($request->post['password']) ? $request->post['password'] : NULL;
    if(empty($passwd)) errorOccured($request, ContextManager::get("i18n")("e_nopass"));
    if($passwd != UserDb::from($sourcePhpRoot, $of_user)["password"]) errorOccured($request, ContextManager::get("i18n")("e_pmm"));
}

function setMyProfilePicture(string $sourcePhpRoot, $ppFilename) {
    UserDb::update($sourcePhpRoot, array("profilePic" => $ppFilename));
}

function getProfilePicture(string $sourcePhpRoot, $user) {
    $config = UserDb::from($sourcePhpRoot, $user);
    if(!$config) return;
    if(!array_key_exists("profilePic", $config)) return;

    $profilePicPath = $config["profilePic"];
    return $profilePicPath;
}

//
//connectivity
//

function getCurrentUserLogged(mixed &$session) {
    return empty($session["loggedAs"]) ? "" : $session["loggedAs"];
}

function isUserLogged(mixed &$session) {
    return !empty(getCurrentUserLogged($session));
}

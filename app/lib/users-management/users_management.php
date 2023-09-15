<?php

include $_SERVER["DOCUMENT_ROOT"] . "/lib/users-management/db.php";

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

function checkUserExists($user, $non_fatal_check = false) {
    $do_exist = UserDb::from($user) != null && file_exists(getInternalUserFolder($user));
    if(!$do_exist && !$non_fatal_check) errorOccured(i18n("e_unsu", $user));
    return $do_exist;
}

function checkPOSTedUserPassword($of_user) {
    $passwd = isset($_POST['password']) ? $_POST['password'] : NULL;
    if(empty($passwd)) errorOccured(i18n("e_nopass"));
    if($passwd != UserDb::from($of_user)["password"]) errorOccured(i18n("e_pmm"));
}

function setMyProfilePicture($ppFilename) {
    UserDb::update(array("profilePic" => $ppFilename));
}

function getProfilePicture($user) {
    $config = UserDb::from($user);
    if(!$config) return;
    if(!array_key_exists("profilePic", $config)) return;

    $profilePicPath = $config["profilePic"];
    return $profilePicPath;
}

//
//connectivity
//

function getCurrentUserLogged() {
    return empty($_SESSION["loggedAs"]) ? "" : $_SESSION["loggedAs"];
}

function isUserLogged() {
    return !empty(getCurrentUserLogged());
}

<?php 

function getServerRootApp() {
    return pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
}

function getCurrentLibraryFileName() {
    return 'current.json';
}

function getUnifiedLibraryFileName() {
    return 'unified.json';
}

function getCurrentShoutFileName() {
    return 'shout.json';
}

function getProfilePicFilename($ext) {
    return "pp.".$ext;
}

function getDefaultBackgroundColors() {
    return array("#EE7752", "#E73C7E", "#23A6D5", "#23D5AB");
}

function getRelativeRootAppUrl() {
    return "/wtnz/";
}

function getAppDescription() {
    return i18n("wtnz_descr");
}

function getWebsocketUrl() {
    return "wss://" . $_SERVER["HTTP_HOST"] . ":3000";
}

function getAppIconUrl() {
    return getAbsoluteRootAppUrl() . "front/assets/img/ico.png";
}

function getAbsoluteRootAppUrl() {
    return $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . getRelativeRootAppUrl();
}

function getDownloadsFolder() {
    return $_SERVER["DOCUMENT_ROOT"] . '/feedtnz/downloads/';
}

define('USERS_DATA_PATH', getServerRootApp() . "/data/users");

function getAppDbPath() {
    return getServerRootApp(). "/data/users.json";
}

function getInternalUserFolder($user) {
    return getServerRootApp() . "/data/users/" . $user . '/';
}

function getPublicUserFolder($user) {
    return getRelativeRootAppUrl() . "data/users/" . $user . '/';
}

////////////////
// OS Related //
////////////////

function getOS() { 

    $os_array = array(
        '/mac/i' =>  'Mac',
        '/win/i' =>  'Windows',
        '/ip/i' =>  'iOS',
        '/android/i' =>  'Android'
    );

    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) return $value;
    }   

    return null;
}

$_DF_OS = array(
    "osx" => "Mac",
    "win" => "Windows"
);

$_OS_DF = array_flip($_DF_OS);

function fromDownloadFolderToOS($folder) {
    global $_DF_OS;
    return array_key_exists($folder, $_DF_OS) ? $_DF_OS[$folder] : null;
}

function fromOSToDownloadFolder($os) {
    global $_OS_DF;
    return array_key_exists($os, $_OS_DF) ? $_OS_DF[$os] : null;
}

////////////////////
// END OS Related //
////////////////////

///////////
// title //
///////////

$_initial_title = "WTNZ";
$_title = null;

function setTitle($superbus) {
    global $_title, $_initial_title;
    if($superbus) $_title .= $_initial_title . " - " . $superbus;
}

function getTitle() {
    global $_title, $_initial_title;
    return $_title ?? $_initial_title;
}

///////////////
// END title //
///////////////
<?php 

include $_SERVER['DOCUMENT_ROOT'] . "/app/config/const.php";

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
    return "/";
}

function getAppDescription() {
    return i18n("app_descr");
}

function getWebsocketUrl() {
    return "wss://" . $_SERVER["HTTP_HOST"] . ":3000";
}

function getAppIconUrl() {
    return "/public/assets/img/ico.png";
}

function getAbsoluteRootAppUrl() {
    return ($_SERVER["REQUEST_SCHEME"] ?: 'http') . "://" . $_SERVER["HTTP_HOST"] . getRelativeRootAppUrl();
}

// TODO
function getDownloadsFolder() {
    return $_SERVER["DOCUMENT_ROOT"] . "/feedtnz/downloads/";
}

/** internal folder path of user's data */
function getInternalAppDbPath() {
    return $_SERVER["DOCUMENT_ROOT"] . "/_data/users.json";
}

/** internal folder path of user's data */
function getInternalUserFolder($user) {
    return $_SERVER["DOCUMENT_ROOT"] . "/_data/users/" . $user . "/";
}

/** web server exposed user's data */
function getPublicUserFolder($user) {
    return getRelativeRootAppUrl() . "data/users/" . $user . "/";
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

$_initial_title = $appName;
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
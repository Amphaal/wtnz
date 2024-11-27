<?php 

/** */
define("APP_NAME", "SoundVitrine");

/** */
define("COMPANION_APP_NAME", "SoundBuddy");

/** when COMPANION_APP_NAME does a library upload, expect the library to be named as */
define("MUSIC_LIB_UPLOAD_FILE_NAME", APP_NAME . "_file");

/** when COMPANION_APP_NAME does a shout, expect this as filename */
define("SHOUT_UPLOAD_FILE_NAME", "shout_file");

/** */
define("MUSIC_LIB_PROFILE_FILE_NAME", 'current.json');

/** */
define("COMPILED_MUSIC_LIB_PROFILE_FILE_NAME", 'unified.json');

/** */
define("SHOUT_PROFILE_FILE_NAME", 'shout.json');

/** where, on the current web server, is exposed the root of the app */
define("WEB_APP_ROOT", "/");

/** since theses colors can be customized by user */
define("DEFAULT_BACKGROUND_COLORS", ["#EE7752", "#E73C7E", "#23A6D5", "#23D5AB"]);

/** */
define("COMPANION_APP_GITHUB_LATEST_RELEASE_URL", "https://github.com/Amphaal/SoundBuddy/releases/latest");

//
//
//

function getWebAppRootFullpath() {
    $request = ContextManager::get("REQUEST");
    return ($request->server['server_protocol']  ?: 'http') . "://" . $request->header['host'] . WEB_APP_ROOT;
}

/** use WSS in production environment */
function getShoutServiceWebsocketRootHost() {
    return ContextManager::get("REQUEST")->header['host'] . "/sentry";
}

/** */
function getProfilePicFilename($ext) {
    return "pp.".$ext;
}

/** web server exposed user's data */
function getPublicUserFolder() {
    return WEB_APP_ROOT . "data/users/";
}


/** web server exposed user's data */
function getPublicUserFolderOf($user) {
    return getPublicUserFolder() . $user . "/";
}

//
//
//

/** */
function getCompanionAppDownloadFolder() {
    return SOURCE_PHP_ROOT . "/../_data/downloads\/";
}

/** internal folder path of user's data */
function getUserDbFilePath () {
    return SOURCE_PHP_ROOT . "/../_data/users/users.json";
}

/** Directory where session files will be stored */
function getSessionStorageDir() {
    return SOURCE_PHP_ROOT . '/../_data/sessions';
}

/**
 * internal folder path of user's data
 */
function getInternalUserFolder(string $user) {
    return SOURCE_PHP_ROOT . "/../_data/users/" . $user . "/";
}

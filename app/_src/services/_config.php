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

/** hostname that gets exposed to the outside world */
define("EXPOSED_HOST", getenv("SOUNDVITRINE_EXPOSED_HOST") ?? gethostname());
define("EXPOSED_SCHEME", getenv("SOUNDVITRINE_EXPOSED_SCHEME") ?? 'https');

/** where, on the current web server, is exposed the root of the app */
/** MUST END WITH A '/' !!! */
define("WEB_APP_ROOT", getenv("SOUNDVITRINE_WEB_APP_ROOT") ?? "/");

    /** */
    define("URI_RESOURCES_QUERY_ROOT", "resources");

        /** */
        define("PUBLIC_PHP_FOLDER_NAME", "public_php");

        /** */
        define("URI_RESOURCES_QUERY_REPO_CHUNK", "repo");

/** since theses colors can be customized by user */
define("DEFAULT_BACKGROUND_COLORS", ["#EE7752", "#E73C7E", "#23A6D5", "#23D5AB"]);

/** */
define("COMPANION_APP_GITHUB_LATEST_RELEASE_URL", "https://github.com/Amphaal/SoundBuddy/releases/latest");

/** */
define("WEBSOCKET_QUERY_STUB", "ws");

//
//
//

function getWebAppRootFullPath() {
    return EXPOSED_SCHEME . "://" . EXPOSED_HOST . WEB_APP_ROOT;
}

/** use WSS in production environment */
function getWebSocketServiceUrl() {
    return EXPOSED_HOST . "/" . WEBSOCKET_QUERY_STUB;
}

/** */
function getProfilePicFilename(string $ext) {
    return "pp.".$ext;
}

/** web server exposed user's data */
function getPublicUserFolderOf(string $user) {
    return WEB_APP_ROOT . URI_RESOURCES_QUERY_ROOT . "/" . URI_RESOURCES_QUERY_REPO_CHUNK . "\/users\/" . $user;
}

//
//
//

/** */
function getCompanionAppDownloadFolder() {
    return STATE_FILES_ROOT . "/downloads";
}

/** Directory where session files will be stored */
function getSessionStorageDir() {
    return STATE_FILES_ROOT . '/sessions';
}

/** internal folder path of user's data */
function getUserDbFilePath () {
    return STATE_FILES_ROOT . "/users/users.json";
}

/**
 * internal folder path of user's data
 */
function getInternalUserFolder(string $user) {
    return STATE_FILES_ROOT . "/users\/" . $user;
}

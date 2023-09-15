<?php

// display errors on http response
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER["DOCUMENT_ROOT"] . "/config.php";

include $_SERVER["DOCUMENT_ROOT"] . "/lib/i18n.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/users-management/users_management.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/web_title.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/web_user-agent.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/css_compiler.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/string_extensions.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/error_handling.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/templating.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/templating.shards.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/file_uploading.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/http.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/magnifik_input.php";

include $_SERVER["DOCUMENT_ROOT"] . "/controllers/uploadMusicLibrary.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/uploadShout.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/manage.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/downloadApp.php";
include $_SERVER["DOCUMENT_ROOT"] . "/controllers/musicLibrary.php";

// handles users sessions, start
session_start();

function init_app() {

    sanitizePOST();

    // get URI elements
    $qs = getQueryString();

    //generate folders if non existing
    checkUserSpecificFolders(); 
    $user_qs =  array_shift($qs);
    if (!empty($user_qs)) {
        $user_qs = strtolower($user_qs);
    }
    $action = array_shift($qs);

    //if no user directory is being accessed
    if(!isset($user_qs)) {
        setTitle(i18n("welcome"));
        injectAndDisplayIntoAdminLayout("layout/admin/components/welcome.php", get_defined_vars()); 
    }
    
    //check if special queries
    if($user_qs == 'manage') return routerManage($action);
    if($user_qs == 'download') return routerDownload($action);

    //else check if user exists
    checkUserExists($user_qs); 

    //router stack
    switch($action) {
        case 'uploadShout': {
            rerouteToUploadShout($user_qs, $action);
        }
        break;

        default: {
            routerUploadMusicLibrary($user_qs, $action);
        }
    }

    //else redirect on misformated/unhandled URI
    if(!empty($action)) home();

    //redirect to user library in last resort
    return routerMusicLibrary($user_qs);
}

init_app();

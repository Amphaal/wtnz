<?php

// display errors on http response
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . "/app/config.php";

include $_SERVER['DOCUMENT_ROOT'] . "/app/lib/i18n.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/lib/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/lib/web_title.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/lib/web_user-agent.php";

include $_SERVER['DOCUMENT_ROOT'] . "/app/back/helpers/_helpers.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/controllers/uploadLib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/controllers/uploadShout.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/controllers/manage.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/controllers/downloadApp.php";
include $_SERVER['DOCUMENT_ROOT'] . "/app/controllers/musicLibrary.php";

// handles users sessions, start
session_start();

function init_app() {

    sanitizePOST();

    // get URI elements
    $qs = getQueryString();

    //generate folders if non existing
    checkUserSpecificFolders(); 
    $user_qs = array_shift($qs);
    $action = array_shift($qs);

    //if no user directory is being accessed
    if(!isset($user_qs)) {
        setTitle(i18n("welcome"));
        includeXMLRSwitch("layout/admin/components/welcome.php", get_defined_vars()); 
    }
    
    //check if special queries
    if($user_qs == 'manage') return routerManage($action);
    if($user_qs == 'download') return routerDownload($action);

    //else check if user exists
    checkUserExists($user_qs); 

    //router stack
    routerUploadLib($user_qs, $action);
    routerUploadShout($user_qs, $action);

    //else redirect on misformated/unhandled URI
    if(!empty($action)) home();

    //redirect to user library in last resort
    return routerLibrary($user_qs);
}

init_app();

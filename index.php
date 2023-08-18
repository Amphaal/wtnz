<?php

//start session
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/helpers/_helpers.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/controllers/uploadLib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/controllers/uploadShout.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/controllers/manage.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/controllers/downloadApp.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/back/controllers/library.php";

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
        includeXMLRSwitch("back/ui/welcome.php", get_defined_vars()); 
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

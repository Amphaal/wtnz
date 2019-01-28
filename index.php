<?php

include_once "config/config.php";
include_once "back/helpers/helpers.php";
include_once "back/controllers/uploadLib.php";
include_once "back/controllers/uploadShout.php";
include_once "back/controllers/manage.php";
include_once "back/controllers/downloadApp.php";

function init_app() {

    //start session
    session_start();

    // get URI elements
    $qs = getQueryString();
    
    //generate folders if non existing
    checkUserSpecificFolders(); 
    $user_qs = array_shift($qs);
    $action = array_shift($qs);

    //if no user directory is being accessed
    if(!isset($user_qs)) return accessIndex(); 
    
    //check if special queries
    if($user_qs == 'manage') return routerManage($action);
    if($user_qs == 'download') return routerDownload($action);

    //else check if user exists
    checkUserExists($user_qs); 

    //router stack
    routerUploadLib($user_qs, $action);
    routerUploadShout($user_qs, $action);

    //else redirect on misformated/unhandled URI
    if(!empty($action) || substr($_SERVER['REQUEST_URI'], -1) == '/') header('Location: /wtnz/' . $user_qs);

    //redirect to user library in last resort
    return accessUserLibrary($user_qs);
}

function accessIndex() {
    include "back/ui/home.php";
    exit;
}

function accessUserLibrary($user_qs) {

    $expectedLibrary = formatUserDataFolder($user_qs) . getCurrentLibraryFileName();
    $expectedShout = formatUserDataFolder($user_qs) . getCurrentShoutFileName();

    //Client variables
    $clientURLLibrary = dirname($_SERVER['REQUEST_URI']) . substr($expectedLibrary, 1);
    $clientURLShout = dirname($_SERVER['REQUEST_URI']) . substr($expectedShout, 1);
    $latestUpdate = filemtime($expectedLibrary);

    include "front/home.php";
    exit;
}

init_app();

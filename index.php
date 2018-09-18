<?php

include_once "config/config.php";
include_once "back/helpers/helpers.php";
include_once "back/uploadLib.php";
include_once "back/uploadShout.php";

function init_app() {

    // get URI elements
    $qs = getQueryString();
    
    //user specific
    checkUserSpecificFolders(); 
    $user_qs = array_shift($qs);
    if(!isset($user_qs)) return accessIndex(); //if no user directory is being accessed
    if($user_qs == 'users_data') return;
    checkUserExists($user_qs); //check if user exists

    //extract action
    $action = array_shift($qs);

    //router stack
    routerUploadLib($user_qs, $action);
    routerUploadShout($user_qs, $action);

    //else redirect on misformated/unhandled URI
    if(!empty($action) || substr($_SERVER['REQUEST_URI'], -1) == '/' ) header('Location: /wtnz/'.$user_qs);

    //redirect to user library in last resort
    return accessUserLibrary($user_qs);
}

function accessIndex() {
    include "back/ui_templates/home.php";
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

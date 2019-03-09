<?php

//start session
session_start();

include_once "config/config.php";
include_once "back/helpers/helpers.php";
include_once "back/controllers/uploadLib.php";
include_once "back/controllers/uploadShout.php";
include_once "back/controllers/manage.php";
include_once "back/controllers/downloadApp.php";
include_once "back/controllers/accessUserLib.php";

function init_app() {

    // get URI elements
    $qs = getQueryString();
    if(array_key_exists('username', $_POST)) $_POST['username'] = trim(strtolower($_POST['username']));
    
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
    return routerAccessUserLib($user_qs);
}

function accessIndex() {
    include "back/ui/home.php";
    exit;
}

init_app();

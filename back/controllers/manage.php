<?php 

include_once "back/helpers/dataGenerator.php";

function routerManage($action) {
    switch($action) {
        case "create":
            return accountCreation();
            break;
        case "disconnect":
            return disconnect();
            break;
        case "compute":
            return compute();
            break;
        case "connect";
        default;
            return login();
            break;
    }
}

function accountCreation() {
    $rules = [
        "username" => ["min" => 6, "max" => 20],
        "password" => ["min" => 8, "max" => 20],
    ];
    
    if($_POST){
        $acr = tryCreatingUser($rules);
        if(!$acr["isError"]) {
            login();
        }
    } 

    include "back/ui/create_account.php";
}

function disconnect() {
    session_unset();
    session_destroy();
    header('location: '. $_SERVER['HTTP_REFERER']);
}

function login() {
    
    if($_POST) {
        $login_result = connectAs($_POST['username'], $_POST['password']);
        if(!$login_result['isError']) {
            goToSelfLibrary();
        }
    }

    include "back/ui/login.php";

}

function compute() {
    $dg = new DataGenerator("amphaal");
    $dg->generateFiles(true);
}
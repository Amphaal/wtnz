<?php 

function routerManage($action) {
    switch($action) {
        case "create":
            return accountCreation();
            break;
        case "disconnect":
            return disconnect();
            break;
        case "connect";
        default;
            return login();
            break;
    }
}

function accountCreation() {
    $minlen_username = 6;
    $maxlen_username = 20;

    $minlen_password = 8;
    $maxlen_password = 20;

    include "back/ui/create_account.php";

    if($_POST){
        var_dump($_POST);
    } 
}

function disconnect() {
    session_unset();
    session_destroy();
    header('location: '. $_SERVER['HTTP_REFERER']);
}

function login() {
    include "back/ui/login.php";

    if($_POST) {
        $login_result = connectAs($_POST['username'], $_POST['password']);
        if(!$login_result['isError']) {
            header('Location: /wtnz/' . $_SESSION["loggedAs"]);
        }
    }

}
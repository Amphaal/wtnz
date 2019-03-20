<?php 

include_once "back/helpers/DataGenerator.php";

function routerManage($action) {
    switch($action) {
        case "create":
            return accountCreation();
            break;
        case "disconnect":
            return disconnect();
            break;
        case "pp":
            return ProfilePic();
            break;
        default;
            return home();
            break;
    }
}

function ProfilePic() {
    //upload
    if($_FILES && isUserLogged()) {
 
        //prepare...
        $currentUser = getCurrentUserLogged();
        $expectedFilename = "file";
        $ext = pathinfo($_FILES[$expectedFilename]['name'], PATHINFO_EXTENSION);
        testUploadedFile($expectedFilename);

        //upload...
        $ppname = getProfilePicFilename($ext);
        $internalDest = getInternalUserFolder($currentUser) . $ppname;
        uploadFile($internalDest, $expectedFilename);

        //remove previous if different ext...
        $currentpicFN = getProfilePicture($currentUser);
        if($currentpicFN && $currentpicFN != $ppname) {
            $currentpicFN = getInternalUserFolder($currentUser) . $currentpicFN;
            @unlink($currentpicFN);
        }

        //update DB
        setMyProfilePicture($ppname);

        //return
        ob_clean(); flush();
        echo getPublicUserFolder($currentUser) . $ppname;
        return;  
    }
}

function home() {

    login();

    //prepare
    $iul = isUserLogged();
    $os = getOs();
    $mylib_loc = getLocation("MyLibrary");
    $dd_folders = array();
    
    //downloads...
    if($iul) {

        $curUser = getCurrentUserLogged();
        $pp = getProfilePicture($curUser);
        $pp_path = null;
        if($pp) $pp_path = getPublicUserFolder($curUser) . $pp;

        if($os) {
            array_push($dd_folders, fromOSToDownloadFolder($os));
        } else {
            array_push($dd_folders, 
                array_keys(availableDownloads())
            );
        }
    }

    //title
    $title = $iul ? "e_log_manage" : "e_log_home";
    setTitle(i18n($title));

    includeXMLRSwitch("back/ui/home.php", get_defined_vars());
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

    includeXMLRSwitch("back/ui/create_account.php", get_defined_vars());
}

function disconnect() {
    session_unset();
    session_destroy();

    if(isXMLHttpRequest()) {
        goToLocation("Home");
    } else { 
        header('location: '. $_SERVER['HTTP_REFERER']);
    }

}

function login() {

    if($_POST) {
        $login_result = connectAs($_POST['username'], $_POST['password']);
        if(!$login_result['isError']) {
            if(isXMLHttpRequest()) {
                goToLocation("Home");
            } else { 
                goToLocation("MyLibrary");
            }
        }
    }
}

function tryCreatingUser($rules) {

    $ret = array("errDescr" => null);

    $user = $_POST['username'];
    $passwd = $_POST['password'];
    $end_checks = false;

    //checks...
    while(!$end_checks && empty($ret["errDescr"])) {

        //fields filed
        foreach($rules as $field => $f_rules) {
            if(empty($field)) {
                $ret["errDescr"] = i18n("crea_miss_p_u", i18n($field));
                continue;
            }
        }

        // is user already logged
        if (isUserLogged()) {
            $ret["errDescr"] = i18n("err_nocreate_onlog");
            continue;
        }
        
        //check user asked to create exists
        if (checkUserExists($user, true)) {
            $ret["errDescr"] = i18n("user_already_exist", $user);
            continue;
        }
        
        //check username over regex
        $isUNOk = null;
        preg_match('/^[a-zA-Z0-9]+([_-]?[a-zA-Z0-9])*$/', $user, $isUNOk);
        if (count($isUNOk) == 0) {
            $ret["errDescr"] = i18n("username_invalid", $user);
            continue;
        }

        //check if min/max length on fields
        foreach($rules as $field => $f_rules) {

            $len = strlen($_POST[$field]);
            $min = $f_rules['min'];
            $max = $f_rules['max'];

            if($len < $min || $len > $max) {
                $ret["errDescr"] = i18n("field_nc_pattern", i18n($field), 
                                        $min, $max);
                continue;
            }
        }

        //checks OK
        $end_checks = true;
    }

    //check if return
    $ret["isError"] = !empty($ret["errDescr"]);
    if($ret["isError"]) return $ret;

    //else create account
    updateUsersConfig(array(
        "password" => $passwd,
        "email" => $_POST['email'],
        "customColors" => randomizeBannerColors()
    ), $user);

    return $ret;

}

function randomizeBannerColors() {
    $getRandColorHex = function() {
        $getRandColorGroup = function() {
            return str_pad(strtoupper(dechex(rand(0, 255))), 2, "0", STR_PAD_LEFT);
        };
        return "#" . $getRandColorGroup() . $getRandColorGroup() . $getRandColorGroup();
    };
    return array($getRandColorHex(), $getRandColorHex(), $getRandColorHex(), $getRandColorHex());
}


function connectAs($user, $passwd) {
    $ret = array("isError" => true, "description" => null);
    
    if(empty($user)) {
        $ret["description"] = i18n("e_log_nouser");
    }
    elseif(empty($passwd))  {
        $ret["description"] = i18n("e_nopass");
    }
    if(isset($_SESSION["loggedAs"]) && $_SESSION["loggedAs"] == $user) {
        $ret["isError"] = false;
        $ret["description"] = i18n("e_log_identical");
    } elseif(getUserConfig($user) == null) {
        $ret["description"] = i18n("e_unsu", $user);
    } elseif($passwd != getUserConfig($user)["password"]) {
        $ret["description"] = i18n("e_pmm");
    } else {
        $ret["isError"] = false;
        $_SESSION["loggedAs"] = $user;
    }
    
    return $ret;
} 
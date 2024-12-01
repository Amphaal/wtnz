<?php 

include SOURCE_PHP_ROOT . "/lib/data-generator/data_generator.php";

function routerInterceptor_Manage(string $qs_action) {
    switch($qs_action) {
        case "create":
            return accountCreation();
        case "disconnect":
            return disconnect();
        case "pp":
            return ProfilePic();
        case "bb":
            return BackgroundBand();
        default;
            return home();
    }
}

function BackgroundBand() {
    //
    $newColors = file_get_contents('php://input');
    if(!$newColors) return;

    //
    $newColors = json_decode($newColors);
    
    if(!isUserLogged()) return;

    //
    UserDb::update([
        "customColors" => $newColors
    ]);

    //
    echo "OK";
    return;
}


function ProfilePic() {
    //upload
    $uploadedFiles = ContextManager::get("REQUEST")->files;
    if($uploadedFiles && isUserLogged()) {
 
        //prepare...
        $currentUser = Session::getLoggedUser();
        $expectedFilename = "file";
        $ext = pathinfo($uploadedFiles[$expectedFilename]['name'], PATHINFO_EXTENSION);
        testUploadedFile($expectedFilename);

        //upload...
        $ppname = getProfilePicFilename($ext);
        $internalDest = getInternalUserFolder($currentUser) . '/' . $ppname;
        uploadFile($internalDest, $expectedFilename);

        //remove previous if different ext...
        $currentpicFN = getProfilePicture($currentUser);
        if($currentpicFN && $currentpicFN != $ppname) {
            $currentpicFN = getInternalUserFolder($currentUser) . '/' . $currentpicFN;
            @unlink($currentpicFN);
        }

        //update DB
        setMyProfilePicture($ppname);

        //return
        ob_clean(); flush();
        echo getPublicUserFolderOf($currentUser) . '/' . $ppname;
        return;  
    }
}

function home() {

    $login_result = null;
    login($login_result);

    //prepare
    $iul = isUserLogged();
    $mylib_loc = getLocation("MyLibrary");
    $is_not_my_lib = true;
    $dd_folders = [];
    
    //if user is logged...
    if($iul) {

        $is_not_my_lib = (getLocation("ThisLibrary") != $mylib_loc);

        //downloads...
        $curUser = Session::getLoggedUser();
        $pp = getProfilePicture($curUser);
        $pp_path = null;
        if($pp) $pp_path = getPublicUserFolderOf($curUser) . '/' . $pp;

        $dd_folders = array_keys(availableDownloads());

    }

    //title
    $title = $iul ? "e_log_manage" : "e_log_home";
    ContextManager::get("set_title")(i18n($title));

    injectAndDisplayIntoAdminLayout("layout/admin/components/home.php", get_defined_vars());
}  



function accountCreation() {
    $rules = [
        "username" => ["min" => 6, "max" => 20],
        "password" => ["min" => 8, "max" => 20],
    ];
    
    $acr = null;
    if(ContextManager::get("REQUEST")->post){
        $acr = tryCreatingUser($rules);
        if(!$acr["isError"]) {
            login();
        }
    }

    injectAndDisplayIntoAdminLayout("layout/admin/components/create_account.php", get_defined_vars());
}

function disconnect() {
    unlink(ContextManager::get("SESSION_FILE"));
    // session_unset();
    // session_destroy();

    if(isXMLHttpRequest()) {
        goToLocation("Home");
    } else { 
        ContextManager::get("header")('location: '. ContextManager::get("REQUEST")->header['referer']);
    }

}

function login(&$login_result = null) {
    //
    $post = ContextManager::get("REQUEST")->post;

    //
    if($post) {
        //
        $login_result = connectAs($post['username'], $post['password']);
        
        //
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
    $ret = ["description" => null];

    $post = ContextManager::get("REQUEST")->post;
    $user = $post['username'];
    $passwd = $post['password'];
    $end_checks = false;

    //checks...
    while(!$end_checks && empty($ret["description"])) {

        //fields filed
        foreach($rules as $field => $f_rules) {
            if(empty($field)) {
                $ret["description"] = i18n("crea_miss_p_u", i18n($field));
                continue;
            }
        }

        // is user already logged
        if (isUserLogged()) {
            $ret["description"] = i18n("err_nocreate_onlog");
            continue;
        }
        
        //check user asked to create exists
        if (checkUserExists($user, true)) {
            $ret["description"] = i18n("user_already_exist", $user);
            continue;
        }
        
        //check username over regex
        $isUNOk = null;
        preg_match('/^[a-zA-Z0-9]+([_-]?[a-zA-Z0-9])*$/', $user, $isUNOk);
        if (count($isUNOk) == 0) {
            $ret["description"] = i18n("username_invalid", $user);
            continue;
        }

        //check if min/max length on fields
        foreach($rules as $field => $f_rules) {

            $len = strlen($post[$field]);
            $min = $f_rules['min'];
            $max = $f_rules['max'];

            if($len < $min || $len > $max) {
                $ret["description"] = i18n("field_nc_pattern", i18n($field), 
                                        $min, $max);
                continue;
            }
        }

        //checks OK
        $end_checks = true;
    }

    //check if return
    $ret["isError"] = !empty($ret["description"]);
    if($ret["isError"]) return $ret;

    //else create account
    UserDb::update([
        "password" => $passwd,
        "email" => $post['email'],
        "customColors" => randomizeBannerColors()
    ], $user);

    //
    return $ret;
}

function randomizeBannerColors() {
    //
    $getRandColorHex = function() {
        //
        $getRandColorGroup = function() {
            return str_pad(strtoupper(dechex(rand(0, 255))), 2, "0", STR_PAD_LEFT);
        };

        //
        return "#" . $getRandColorGroup() . $getRandColorGroup() . $getRandColorGroup();
    };

    //
    return [
        $getRandColorHex(), 
        $getRandColorHex(), 
        $getRandColorHex(), 
        $getRandColorHex()
    ];
}


function connectAs($user, $passwd) {
    $ret = ["isError" => true, "description" => null];

    if(empty($user)) {
        $ret["description"] = i18n("e_log_nouser");
    }
    elseif(empty($passwd))  {
        $ret["description"] = i18n("e_nopass");
    }
    elseif(Session::getLoggedUser() == $user) {
        $ret["isError"] = false;
        $ret["description"] = i18n("e_log_identical");
    } elseif(UserDb::from($user) == null) {
        $ret["description"] = i18n("e_unsu", $user);
    } elseif($passwd != UserDb::from($user)["password"]) {
        $ret["description"] = i18n("e_pmm");
    } else {
        $ret["isError"] = false;
        Session::setLoggedUser($user);
    }
    
    return $ret;
}

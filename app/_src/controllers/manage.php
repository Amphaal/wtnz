<?php 

include $sourcePhpRoot . "/lib/data-generator/data_generator.php";

function routerInterceptor_Manage(string $qs_action, string $sessionFile, mixed &$session, string $sourcePhpRoot, $request) {
    switch($qs_action) {
        case "create":
            return accountCreation($sourcePhpRoot, $session, $request);
        case "disconnect":
            return disconnect($sessionFile, $request);
        case "pp":
            return ProfilePic($sourcePhpRoot, $session, $request);
        case "bb":
            return BackgroundBand($sourcePhpRoot, $session);
        default;
            return home($sourcePhpRoot, $session, $request);
    }
}

function BackgroundBand(string $sourcePhpRoot, mixed &$session) {
    //
    $newColors = file_get_contents('php://input');
    if(!$newColors) return;

    //
    $newColors = json_decode($newColors);
    
    if(!isUserLogged($session)) return;

    //
    UserDb::update(
        $sourcePhpRoot,
        array("customColors" => $newColors)
    );

    //
    echo "OK";
    return;
}


function ProfilePic(string $sourcePhpRoot, mixed &$session, $request) {
    //upload
    if($request->files && isUserLogged($session)) {
 
        //prepare...
        $currentUser = getCurrentUserLogged($session);
        $expectedFilename = "file";
        $ext = pathinfo($request->files[$expectedFilename]['name'], PATHINFO_EXTENSION);
        testUploadedFile($request, $expectedFilename);

        //upload...
        $ppname = getProfilePicFilename($ext);
        $internalDest = getInternalUserFolder($sourcePhpRoot, $currentUser) . $ppname;
        uploadFile($request, $internalDest, $expectedFilename);

        //remove previous if different ext...
        $currentpicFN = getProfilePicture($sourcePhpRoot, $currentUser);
        if($currentpicFN && $currentpicFN != $ppname) {
            $currentpicFN = getInternalUserFolder($sourcePhpRoot, $currentUser) . $currentpicFN;
            @unlink($currentpicFN);
        }

        //update DB
        setMyProfilePicture($sourcePhpRoot, $ppname);

        //return
        ob_clean(); flush();
        echo getPublicUserFolderOf($currentUser) . $ppname;
        return;  
    }
}

function home(string $sourcePhpRoot, mixed &$session, $request) {

    $login_result = null;
    login($sourcePhpRoot, $request, $login_result);

    //prepare
    $iul = isUserLogged($session);
    $mylib_loc = getLocation($request, "MyLibrary");
    $is_not_my_lib = true;
    $dd_folders = array();
    
    //if user is logged...
    if($iul) {

        $is_not_my_lib = (getLocation($request, "ThisLibrary") != $mylib_loc);

        //downloads...
        $curUser = getCurrentUserLogged($session);
        $pp = getProfilePicture($sourcePhpRoot, $curUser);
        $pp_path = null;
        if($pp) $pp_path = getPublicUserFolderOf($curUser) . $pp;

        $dd_folders = array_keys(availableDownloads($sourcePhpRoot));

    }

    //title
    $title = $iul ? "e_log_manage" : "e_log_home";
    ContextManager::get("set_title")(ContextManager::get("i18n")($title));

    ContextManager::get("injectAndDisplayIntoAdminLayout")("layout/admin/components/home.php", get_defined_vars());
}  



function accountCreation(string $sourcePhpRoot, mixed &$session, $request) {
    $rules = [
        "username" => ["min" => 6, "max" => 20],
        "password" => ["min" => 8, "max" => 20],
    ];
    
    $acr = null;
    if($request->post){
        $acr = tryCreatingUser($sourcePhpRoot, $session, $request, $rules);
        if(!$acr["isError"]) {
            login($sourcePhpRoot, $session, $request);
        }
    }

    ContextManager::get("injectAndDisplayIntoAdminLayout")("layout/admin/components/create_account.php", get_defined_vars());
}

function disconnect($sessionFile, $request) {
    unlink($sessionFile);
    // session_unset();
    // session_destroy();

    if(isXMLHttpRequest($request)) {
        goToLocation($request, "Home");
    } else { 
        ContextManager::get("header")('location: '. $request->header['referer']);
    }

}

function login(string $sourcePhpRoot, mixed &$session, $request, &$login_result = null) {

    if($request->post) {
        $login_result = connectAs($sourcePhpRoot, $session, $request->post['username'], $request->post['password']);
        
        if(!$login_result['isError']) {
            if(isXMLHttpRequest($request)) {
                goToLocation($request, "Home");
            } else { 
                goToLocation($request, "MyLibrary");
            }
        }
    }
}

function tryCreatingUser(string $sourcePhpRoot, mixed &$session, $request, $rules) {

    $ret = array("description" => null);

    $user = $request->post['username'];
    $passwd = $request->post['password'];
    $end_checks = false;

    //checks...
    while(!$end_checks && empty($ret["description"])) {

        //fields filed
        foreach($rules as $field => $f_rules) {
            if(empty($field)) {
                $ret["description"] = ContextManager::get("i18n")("crea_miss_p_u", ContextManager::get("i18n")($field));
                continue;
            }
        }

        // is user already logged
        if (isUserLogged($session)) {
            $ret["description"] = ContextManager::get("i18n")("err_nocreate_onlog");
            continue;
        }
        
        //check user asked to create exists
        if (checkUserExists($request, $user, true)) {
            $ret["description"] = ContextManager::get("i18n")("user_already_exist", $user);
            continue;
        }
        
        //check username over regex
        $isUNOk = null;
        preg_match('/^[a-zA-Z0-9]+([_-]?[a-zA-Z0-9])*$/', $user, $isUNOk);
        if (count($isUNOk) == 0) {
            $ret["description"] = ContextManager::get("i18n")("username_invalid", $user);
            continue;
        }

        //check if min/max length on fields
        foreach($rules as $field => $f_rules) {

            $len = strlen($request->post[$field]);
            $min = $f_rules['min'];
            $max = $f_rules['max'];

            if($len < $min || $len > $max) {
                $ret["description"] = ContextManager::get("i18n")("field_nc_pattern", ContextManager::get("i18n")($field), 
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
    UserDb::update($sourcePhpRoot,
    array(
        "password" => $passwd,
        "email" => $request->post['email'],
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


function connectAs(string $sourcePhpRoot, mixed &$session, $user, $passwd) {
    $ret = array("isError" => true, "description" => null);
    
    if(empty($user)) {
        $ret["description"] = ContextManager::get("i18n")("e_log_nouser");
    }
    elseif(empty($passwd))  {
        $ret["description"] = ContextManager::get("i18n")("e_nopass");
    }
    if(isset($session["loggedAs"]) && $session["loggedAs"] == $user) {
        $ret["isError"] = false;
        $ret["description"] = ContextManager::get("i18n")("e_log_identical");
    } elseif(UserDb::from($sourcePhpRoot, $user) == null) {
        $ret["description"] = ContextManager::get("i18n")("e_unsu", $user);
    } elseif($passwd != UserDb::from($sourcePhpRoot, $user)["password"]) {
        $ret["description"] = ContextManager::get("i18n")("e_pmm");
    } else {
        $ret["isError"] = false;
        $session["loggedAs"] = $user;
    }
    
    return $ret;
} 
<?php

function getQueryString() {
    $request_uri = explode('/', strtolower($_SERVER['REQUEST_URI']));
    $request_uri = array_filter($request_uri, 'strlen' );
    array_shift($request_uri);
    return $request_uri;
}

function errorOccured($error_text) {
    if(isset($_POST['headless'])) http_response_code(520);
    exit($error_text); 
    //throw new Exception($error_text);
}

function mayCreateUserDirectory($directory) {
    $shouldWrite = !file_exists($directory);
    if (!$shouldWrite) return null;

    $result = mkdir($directory, 0777, true);
    if (!$result) 
    {
        errorOccured(i18n("e_wdu", $directory));
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
        $isUNOk;
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

function updateUsersConfig($myNewConfig, $user = null) {
    if($user == null) $user = getCurrentUserLogged();
    
    $users = getUserDb();
    $users[$user] =  $myNewConfig;
    updateUserDb($users);
    Config::forceUpdate();
}

function checkUserSpecificFolders() {
    //for each user
    $p = getConfig()['users'];
    foreach($p as $user => $pass) {
        $path = getInternalUserFolder($user);
        mayCreateUserDirectory($path);
    }
}  

function getInternalUserFolder($user) {
    $path = USERS_DATA_PATH . '/' . $user . '/';
    return $path;
}

function getPublicUserFolder($user) {
    $path = "./" . USERS_DATA_RPATH . '/' . $user . '/';
    return $path;
}

function checkUserExists($user, $non_fatal_check = false) {
    $do_exist = isset(getConfig()['users'][$user]) && file_exists(getInternalUserFolder($user));
    if(!$do_exist && !$non_fatal_check) errorOccured(i18n("e_unsu", $user));
    return $do_exist;
}

function comparePasswords($user) {
    $passwd = isset($_POST['password']) ? $_POST['password'] : NULL;
    if(empty($passwd)) errorOccured(i18n("e_nopass"));
    if($passwd != getConfig()['users'][$user]["password"]) errorOccured(i18n("e_pmm"));
}

function testUploadedFile($expectedFilename){
    $fileToUpload = isset($_FILES[$expectedFilename]) ? $_FILES[$expectedFilename] : NULL;
    if(empty($fileToUpload)) errorOccured(i18n("e_upLibMiss"));
    if($fileToUpload['error'] == 4 ) errorOccured(i18n("e_noFUp"));
    if($fileToUpload['error'] > 0 ) errorOccured(i18n("e_upErr"));
}

function uploadFile($pathTo, $expectedFilename) {
        $uploadResult = move_uploaded_file($_FILES[$expectedFilename]['tmp_name'], $pathTo);
        if(!$uploadResult) errorOccured(i18n("e_upErr"));
}

function testFileCompatibility($expectedFilename) {
    $fileContent = file_get_contents($_FILES[$expectedFilename]['tmp_name']);
    
    //check if JSON compliant
    $result = json_decode($fileContent);
    if (json_last_error() !== JSON_ERROR_NONE) errorOccured(i18n("e_ucJSON"));
}

function isUselessUpload($targetPath, $expectedFilename) {
    //check for duplicate in current / uploaded file
    if (!file_exists($targetPath)) return false;
    $hash_uploaded = hash_file('sha1',$_FILES[$expectedFilename]['tmp_name']);
    $hash_current = hash_file('sha1', $targetPath);
    return $hash_uploaded == $hash_current ? true : false;
}

function getCurrentLibraryFileName() {
    return 'current.json';
}

function getCurrentShoutFileName() {
    return 'shout.json';
}

function getFileUploadLimit(){
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}


function getFilesInFolder($path_to) {
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));
    $ret = [];
    foreach($files as $file) { 
        array_push($ret, $path_to . '/' . $file);
    }
    return $ret;
}

//
//conectivity
//

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
    } elseif(!isset(getConfig()['users'][$user])) {
        $ret["description"] = i18n("e_unsu", $user);
    } elseif($passwd != getConfig()['users'][$user]["password"]) {
        $ret["description"] = i18n("e_pmm");
    } else {
        $ret["isError"] = false;
        $_SESSION["loggedAs"] = $user;
    }
    
    return $ret;
} 

function getCurrentUserLogged() {
    return empty($_SESSION["loggedAs"]) ? "" : $_SESSION["loggedAs"];
}

function isUserLogged() {
    return !empty(getCurrentUserLogged());
}

function goToSelfLibrary() {
    header('Location: /wtnz/' . getCurrentUserLogged());
}

//Render HTTP pattern from values
function renHpat($rules) {
    return ".{". $rules['min'] . "," . $rules['max'] . "}";
}


function getMyConfig() {
   if(isUserLogged()) {
        return getUsersConfig(getCurrentUserLogged());
    }
}

function getUsersConfig($user) {
    return getConfig()['users'][$user];
 }

//POST remember
function PRem($post_val) {
    return isset($_POST[$post_val]) ? $_POST[$post_val] : "";
}
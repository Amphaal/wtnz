<?php 

function _wAnim($owner) {
    return '<div class="wAnim" data-owner="'. $owner .'"></div>';
}

function getFileUploadLimit(){
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    return min($max_upload, $max_post, $memory_limit) * 1000;
}
 
//Render HTTP pattern from values
function renHpat($rules) {
    return ".{". $rules['min'] . "," . $rules['max'] . "}";
}

//POST remember
function PRem($post_val) {
    return isset($_POST[$post_val]) ? $_POST[$post_val] : "";
}

/** does an echo of each files contained in $path_to_dir folder (not recursive) */
function echoFilesOfFolder($path_to_dir) {
    foreach(getFilesInFolder($path_to_dir) as $file) { 
        echo file_get_contents($file);
    }
}

/** list alls files within a folder (not recursive) */
function getFilesInFolder($path_to) {
    $files = scandir($path_to); 
    $files = array_diff($files, array('..', '.'));
    $ret = [];
    foreach($files as $file) { 
        array_push($ret, $path_to . "/" . $file);
    }
    return $ret;
}

function _btnLink($url, $forceWLocation = false, $XMLR_noBackButton = false) {
    
    $out = "";
    
    if(isXMLHttpRequest() && !$forceWLocation) {
        $out ='href="' . $url . "\"";
    } else {
        $out = 'onclick="window.location=\'' . $url . "\"\"";
    }

    if(isXMLHttpRequest() && $XMLR_noBackButton) {
        $out .= " no-back";
    }

    echo $out;
}

function _popup($result) {
    if(empty($result)) return;
    $type = $result["isError"] == true ? "error" : "info";
    return "<div onclick=\"_popup(event)\" class=\"popup " . $type . '"><div class="content">' . $result["description"] . '</div></div>';
}

function _magnifikInput($params, $rules = null) {
    $newInput = array();
    $newContainer = array();
    $descr = "";

    $toPh = function($val) { return 'placeholder="' . $val . "\"";};
    $toVal = function($val) { return 'value="' . $val ."\"";};

    //if type unset, set default
    if(!array_key_exists('type', $params)) $params['type'] = "text";

    //autoset name depending on type
    if(in_array($params['type'], array("password", "email"))) {
        $params["name"] = $params["type"];
    }

    $inputName = $params["name"];

    //if required, specific binding
    if(array_key_exists('required', $params)) {
        array_push($newInput, "required");
        unset($params["required"]);
    }

    //placeholder helper
    if(array_key_exists('placeholder', $params)) {
        
        $trad = $toPh(
            i18n($params["placeholder"])
        );
        
        array_push($newContainer, $trad);
        unset($params["placeholder"]);
    }

    //value helper
    $prem = PRem($inputName);
    if($prem) {
        $prem = $toVal($prem);
        array_push($newInput, $prem);
    }

    //rules helper
    if($rules && $rules[$inputName]) {
        array_push($newInput, 'pattern="' .  renHpat($rules[$inputName]) . "\"");
        $content = i18n("e_log_rule", $rules[$inputName]["min"], $rules[$inputName]["max"]);
        array_push($newInput, $toPh($content));
    }

    //default parsing
    foreach($params as $key => $value) {
        array_push($newInput, $key . "=\"" . $value . "\"");
    }

    $impl = function($arr) { return implode(" ", $arr);};

    return "<div class='magnifik' " . $impl($newContainer) . " >
                <input " . $impl($newInput) . " />
            </div>";
}


function includeXMLRSwitch($inside_part, $included_vars_array) {

    foreach($included_vars_array as $varname => $value) {
        $$varname = $value;
    }

    unset($included_vars_array);
    unset($varname);
    unset($value);

    if(isXMLHttpRequest()) {
        include $inside_part;
    } else {
        include $_SERVER['DOCUMENT_ROOT'] . "/app/layout/admin/entrypoint.php";
    }

    exit;
    
}

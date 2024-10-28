<?php

/** */
function renderMagnifikInput($request, $params, $rules = null) {
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
            ContextManager::get("i18n")($params["placeholder"])
        );
        
        array_push($newContainer, $trad);
        unset($params["placeholder"]);
    }

    //value helper
    $prem = _PRem($request, $inputName);
    if($prem) {
        $prem = $toVal($prem);
        array_push($newInput, $prem);
    }

    //rules helper
    if($rules && $rules[$inputName]) {
        array_push($newInput, 'pattern="' .  _renHpat($rules[$inputName]) . "\"");
        $content = ContextManager::get("i18n")("e_log_rule", $rules[$inputName]["min"], $rules[$inputName]["max"]);
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

//Render HTTP pattern from values
function _renHpat($rules) {
    return ".{". $rules['min'] . "," . $rules['max'] . "}";
}

//POST remember
function _PRem($request, $post_val) {
    return isset($request->post[$post_val]) ? $request->post[$post_val] : "";
}
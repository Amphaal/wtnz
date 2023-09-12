<?php 

function _wAnim($owner) {
    return '<div class="wAnim" data-owner="'. $owner .'"></div>';
}



function _popup($result) {
    if(empty($result)) return;
    $type = $result["isError"] == true ? "error" : "info";
    return "<div onclick=\"_popup(event)\" class=\"popup " . $type . '"><div class="content">' . $result["description"] . '</div></div>';
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

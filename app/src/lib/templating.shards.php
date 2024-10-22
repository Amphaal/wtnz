<?php 

function _wAnim($owner) {
    return '<div class="wAnim" data-owner="'. $owner .'"></div>';
}

function mayDisplayPopup($result) {
    if(empty($result)) return;
    $type = $result["isError"] == true ? "error" : "info";
    echo "<div onclick=\"_popup(event)\" class=\"popup " . $type . '"><div class="content">' . $result["description"] . '</div></div>';
}

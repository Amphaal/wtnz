<?php

///////////
// title //
///////////

$_initial_title = constant("APP_NAME");
$_title = null;

function setTitle($superbus) {
    global $_title, $_initial_title;
    if($superbus) $_title .= $_initial_title . " - " . $superbus;
}

function getTitle() {
    global $_title, $_initial_title;
    return $_title ?? $_initial_title;
}

///////////////
// END title //
///////////////
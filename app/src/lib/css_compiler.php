<?php

function cbacToCss($target = null, $colours = null) {
    if(!$target || !$colours) {
        $colours = constant("DEFAULT_BACKGROUND_COLORS");
        $target = "";
    } else {
        $target = '[data-owner="' . $target . "\"]";
    }
    
    $css = ".wAnim" . $target ."::after { background: linear-gradient(-45deg, %s, %s, %s, %s);}";
    $css = sprintf($css, ...$colours);
    return $css;
}
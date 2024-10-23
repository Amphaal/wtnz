<?php

function errorOccured($request, $error_text) {
    if(isset($request->post['headless'])) http_response_code(520);
    exit($error_text); 
    //throw new Exception($error_text);
}

<?php

function errorOccured($request, $error_text) {
    if(isset($request->post['headless'])) {
        ContextManager::get("http_response_code")(520);
    }
    ContextManager::get("exit")($error_text); 
    //throw new Exception($error_text);
}

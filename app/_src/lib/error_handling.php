<?php

function errorOccured($error_text) {
    if(isset(ContextManager::get("REQUEST")->post['headless'])) {
        ContextManager::get("http_response_code")(520);
    }
    ContextManager::get("exit")($error_text); 
    //throw new Exception($error_text);
}

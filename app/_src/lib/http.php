<?php

/**
 * may return empty array on "/" or "/?..."
 */
function getQueryString($request_uri = null) {
    if(!$request_uri) $request_uri = ContextManager::get("REQUEST")->server['request_uri'];
    $request_uri = explode('/', $request_uri); // split components on slashes
    $request_uri = array_filter($request_uri, 'strlen' ); // remove non-empty
    $request_uri = array_values($request_uri); // reapply indexes (if any removes)

    //
    if(!empty($request_uri)) {
        //
        // lets check if last URL component contains "?" params splitter, and if so, ignores params into returning array
        //

        // get last component
        $request_maybe_params_raw = $request_uri[array_key_last($request_uri)];

        // explode it so we remove params from URL
        $last_url_component = explode('?', $request_maybe_params_raw)[0]; // grab index 0; only get URL part, not params !
        
        // replace last component without params in-array
        $request_uri[array_key_last($request_uri)] = $last_url_component;
    }

    //
    return $request_uri;
}

function goToLocation($rq) {
    ContextManager::get("header")("Location: " . getLocation($rq));
}

function getLocation($rq, $abs = null) {
    $r = $abs ? getWebAppRootFullPath() : WEB_APP_ROOT;

    switch($rq) {
        //
        case "Home": {
            $r .= 'manage';
        }
        break;

        //
        case "ThisLibrary": {
            $url = null;

            //
            if(isXMLHttpRequest())  {
                $temp = getQueryString(ContextManager::get("REQUEST")->header['referer']);
                array_shift($temp); //domain removal
                $url = implode("/", $temp);
            }

            //
            $r .= "u/" . (getQueryString($url)[1] ?? "");
        }
        break;
        
        //
        case "MyLibrary": {
            $r .= "u/" . Session::getLoggedUser();
        }
        break;
    }

    return strtolower($r);
}

function isXMLHttpRequest(){
    $headers = ContextManager::get("REQUEST")->header;
    return isset($headers['x-requested-with']) 
        && strtolower($headers['x-requested-with']) === 'xmlhttprequest';
}

function forceXMLHttp($force) {
   if($force) {
    $request = ContextManager::get("REQUEST");
    $request->header['x-requested-with'] = "xmlhttprequest";
    ContextManager::set("REQUEST", $request);
   } 
}